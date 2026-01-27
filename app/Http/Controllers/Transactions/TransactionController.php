<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TypeBouteille;
use App\Models\Stock;
use App\Models\Client;
use Illuminate\Http\Request;
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
        return view('transactions.create', compact('types', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:echange_simple,echange_type,achat_simple,echange_differe',
            'id_client' => 'nullable|exists:clients,id',
            'id_type_bouteille' => 'required|exists:types_bouteilles,id',
            'id_type_ancien' => 'nullable|exists:types_bouteilles,id',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'montant_total' => 'required|numeric|min:0',
            'mode_paiement' => 'nullable|string|max:50',
            'commentaire' => 'nullable|string|max:500',
        ]);

        $type = TypeBouteille::find($validated['id_type_bouteille']);
        $stock = $type->stock;

        // Vérification du stock de bouteilles pleines
        if ($stock->quantite_pleine < $validated['quantite']) {
            return back()->with('error', 'Stock insuffisant de bouteilles pleines');
        }

        // Gestion du stock en fonction du type de transaction
        switch ($validated['type']) {
            case 'echange_simple':
            case 'echange_type':
            case 'echange_differe':
                // Client retourne des vides, reçoit des pleines
                $stock->quantite_pleine -= $validated['quantite'];
                $stock->quantite_vide += $validated['quantite'];
                break;

            case 'achat_simple':
                // Client achète sans retourner de vides
                $stock->quantite_pleine -= $validated['quantite'];
                // Pas d'augmentation des vides
                break;
        }
        $stock->save();

        // Si échange d'un type différent, mettre à jour l'ancien type
        if ($validated['type'] === 'echange_type' && isset($validated['id_type_ancien'])) {
            $typeAncien = TypeBouteille::find($validated['id_type_ancien']);
            $stockAncien = $typeAncien->stock;
            $stockAncien->quantite_vide -= $validated['quantite'];
            $stockAncien->save();
        }

        // Création de la transaction
        $transaction = Transaction::create([
            'numero_transaction' => 'TRX-' . date('YmdHis') . '-' . rand(1000, 9999),
            'type' => $validated['type'],
            'client_id' => $validated['id_client'],
            'type_bouteille_id' => $validated['id_type_bouteille'],
            'quantite' => $validated['quantite'],
            'prix_unitaire' => $validated['prix_unitaire'],
            'montant_total' => $validated['montant_total'],
            'montant_net' => $validated['montant_total'],
            'montant_reduction' => 0,
            'consigne_montant' => 0,
            'mode_paiement' => $validated['mode_paiement'],
            'administrateur_id' => Auth::id(),
            'commentaire' => $validated['commentaire'],
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction enregistrée');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['client', 'typeBouteille', 'administrateur']);
        return view('transactions.show', compact('transaction'));
    }
}
