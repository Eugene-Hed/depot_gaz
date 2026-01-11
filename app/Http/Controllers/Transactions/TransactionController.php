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
            'type' => 'required|in:vente,echange,consigne,retour,recharge',
            'id_client' => 'nullable|exists:clients,id',
            'id_type_bouteille' => 'required|exists:types_bouteilles,id',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'montant_total' => 'required|numeric|min:0',
            'mode_paiement' => 'nullable|string|max:50',
            'commentaire' => 'nullable|string|max:500',
        ]);

        $type = TypeBouteille::find($validated['id_type_bouteille']);
        $stock = $type->stock;

        // Vérification du stock
        if ($validated['type'] !== 'retour' && $stock->quantite_pleine < $validated['quantite']) {
            return back()->with('error', 'Stock insuffisant');
        }

        // Mise à jour du stock
        if ($validated['type'] !== 'retour') {
            $stock->quantite_pleine -= $validated['quantite'];
            if (in_array($validated['type'], ['vente', 'consigne', 'recharge'])) {
                $stock->quantite_vide += $validated['quantite'];
            }
            $stock->save();
        } else {
            $stock->quantite_pleine += $validated['quantite'];
            $stock->quantite_vide -= $validated['quantite'];
            $stock->save();
        }

        // Création de la transaction
        $transaction = Transaction::create([
            'type' => $validated['type'],
            'client_id' => $validated['id_client'],
            'type_bouteille_id' => $validated['id_type_bouteille'],
            'quantite' => $validated['quantite'],
            'prix_unitaire' => $validated['prix_unitaire'],
            'montant_total' => $validated['montant_total'],
            'mode_paiement' => $validated['mode_paiement'],
            'administrateur_id' => Auth::id(),
            'commentaire' => $validated['commentaire'],
        ]);

        // Points fidélité
        if ($validated['id_client'] && $validated['type'] === 'vente') {
            $client = Client::find($validated['id_client']);
            $points = intval($validated['montant_total'] / 100); // 1 point per 100 FCFA
            $client->ajouterPoints($points);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaction enregistrée');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['client', 'typeBouteille', 'administrateur']);
        return view('transactions.show', compact('transaction'));
    }
}
