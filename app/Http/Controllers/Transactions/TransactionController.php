<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TypeBouteille;
use App\Models\Stock;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\MouvementStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['client', 'typeBouteille'])
            ->latest()
            ->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $types = TypeBouteille::with(['marque', 'stock'])->get();
        $clients = Client::get();
        $recentTransactions = Transaction::with(['client', 'typeBouteille'])
            ->latest()
            ->take(5)
            ->get();
            
        return view('transactions.create', compact('types', 'clients', 'recentTransactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:echange_simple,echange_type,achat_simple',
            'id_client' => 'nullable|exists:clients,id',
            'id_type_bouteille' => 'required|exists:types_bouteilles,id',
            'id_type_ancien' => 'nullable|exists:types_bouteilles,id',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'montant_total' => 'required|numeric|min:0',
            'mode_paiement' => 'nullable|string|max:50',
            'commentaire' => 'nullable|string|max:500',
        ]);

        $type = TypeBouteille::with('stock')->findOrFail($validated['id_type_bouteille']);
        $stock = $type->stock;

        if (!$stock) {
            return back()->with('error', 'Le stock pour ce type de bouteille n\'est pas initialisé.');
        }

        // Vérification du stock de bouteilles pleines
        if ($stock->quantite_pleine < $validated['quantite']) {
            return back()->with('error', 'Stock insuffisant de bouteilles pleines');
        }

        $prixUnitaire = 0;
        $consigneMontant = 0;

        // Tarification Backend Automatique
        switch ($validated['type']) {
            case 'achat_simple':
                // Client n'a pas de bouteille : il paie le fer (consigne) + le gaz (recharge)
                $prixUnitaire = $type->prix_consigne + $type->prix_recharge;
                $consigneMontant = $type->prix_consigne * $validated['quantite'];
                break;

            case 'echange_simple':
                // Client ramène le même type : il paie UNIQUEMENT la recharge (gaz)
                $prixUnitaire = $type->prix_recharge;
                $consigneMontant = 0;
                break;

            case 'echange_type':
                // Priorité au prix saisi par le gérant (libre cours au gérant)
                $prixUnitaire = $validated['prix_unitaire'];
                
                // On calcule quand même la consigne théorique pour les rapports si possible
                if (isset($validated['id_type_ancien'])) {
                    $typeAncien = TypeBouteille::find($validated['id_type_ancien']);
                    if ($typeAncien) {
                        $diffConsigne = $type->prix_consigne - $typeAncien->prix_consigne;
                        if ($diffConsigne > 0) {
                            $consigneMontant = $diffConsigne * $validated['quantite'];
                        }
                    }
                }
                break;
        }

        $montantTotal = $prixUnitaire * $validated['quantite'];

        // Gestion du stock et des mouvements
        \DB::transaction(function () use ($validated, $type, $stock, $prixUnitaire, $montantTotal, $consigneMontant, $request) {
            
            $quantite = $validated['quantite'];

            // 1. Sortie de la bouteille pleine
            $stock->quantite_pleine -= $quantite;
            
            // 2. Entrée de la bouteille vide (sauf achat simple)
            if ($validated['type'] !== 'achat_simple') {
                if ($validated['type'] === 'echange_type' && isset($validated['id_type_ancien'])) {
                    // Vide entre dans le stock de l'ANCIEN type
                    $typeAncien = TypeBouteille::findOrFail($validated['id_type_ancien']);
                    $stockAncien = $typeAncien->stock;
                    $stockAncien->quantite_vide += $quantite;
                    $stockAncien->save();

                    // Mouvement pour l'ancien type (Entrée de vide)
                    MouvementStock::create([
                        'stock_id' => $stockAncien->id,
                        'type_mouvement' => 'entree',
                        'quantite_pleine' => 0,
                        'quantite_vide' => $quantite,
                        'motif' => 'Échange type (Retour ancien)',
                        'administrateur_id' => Auth::id(),
                    ]);
                } else {
                    // Vide entre dans le stock du MÊME type
                    $stock->quantite_vide += $quantite;

                    // Mouvement Entrée de vide (traité globalement plus bas)
                }
            }

            $stock->save();

            // Mouvement pour le nouveau type (Sortie de pleine)
            MouvementStock::create([
                'stock_id' => $stock->id,
                'type_mouvement' => 'sortie',
                'quantite_pleine' => $quantite,
                'quantite_vide' => 0,
                'motif' => 'Vente/Échange (' . $validated['type'] . ')',
                'administrateur_id' => Auth::id(),
            ]);

            // Mouvement Entrée de vide pour le même type
            if ($validated['type'] === 'echange_simple') {
                 MouvementStock::create([
                    'stock_id' => $stock->id,
                    'type_mouvement' => 'entree',
                    'quantite_pleine' => 0,
                    'quantite_vide' => $quantite,
                    'motif' => 'Échange (Retour vide)',
                    'administrateur_id' => Auth::id(),
                ]);
            }

            // 3. Création de la transaction
            $transaction = Transaction::create([
                'numero_transaction' => 'TRX-' . date('YmdHis') . '-' . rand(1000, 9999),
                'type' => $validated['type'],
                'client_id' => $validated['id_client'],
                'type_bouteille_id' => $validated['id_type_bouteille'],
                'quantite' => $quantite,
                'prix_unitaire' => $prixUnitaire,
                'montant_total' => $montantTotal,
                'montant_net' => $montantTotal,
                'montant_reduction' => 0,
                'consigne_montant' => $consigneMontant,
                'mode_paiement' => $validated['mode_paiement'],
                'administrateur_id' => Auth::id(),
                'commentaire' => $validated['commentaire'],
            ]);

            // 4. Points de fidélité
            if ($validated['id_client']) {
                $client = Client::find($validated['id_client']);
                if ($client) {
                    $points = (int) floor($montantTotal / 100);
                    $client->ajouterPoints($points);
                }
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaction enregistrée avec succès');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['client', 'typeBouteille', 'administrateur']);
        return view('transactions.show', compact('transaction'));
    }
}
