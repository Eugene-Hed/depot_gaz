<?php

namespace App\Http\Controllers\Paiements;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Transaction;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    public function index()
    {
        $paiements = Paiement::with(['transaction', 'client', 'administrateur'])
            ->latest()
            ->paginate(20);
        return view('paiements.index', compact('paiements'));
    }

    public function create()
    {
        $transactions = Transaction::where('statut_paiement', '!=', 'paye')
            ->with(['client', 'typeBouteille'])
            ->get();
        $clients = Client::where('statut', 'actif')->get();
        return view('paiements.create', compact('transactions', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'client_id' => 'required|exists:clients,id',
            'montant_paye' => 'required|numeric|min:0.01',
            'mode_paiement' => 'required|in:especes,cheque,virement,carte',
            'reference_cheque' => 'nullable|string|max:255',
            'reference_virement' => 'nullable|string|max:255',
            'reference_carte' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
            'date_paiement' => 'required|date',
        ]);

        $paiement = Paiement::create([
            ...$validated,
            'statut' => 'recu',
            'administrateur_id' => Auth::id(),
        ]);

        // Mettre à jour le statut de la transaction si entièrement payée
        $transaction = Transaction::find($validated['transaction_id']);
        $totalPaid = $transaction->paiements()->sum('montant_paye') + $validated['montant_paye'];
        
        if ($totalPaid >= $transaction->montant_net) {
            $transaction->update(['statut_paiement' => 'paye']);
        } else {
            $transaction->update(['statut_paiement' => 'partiellement_paye']);
        }

        // Mettre à jour le solde de la dette client
        $client = Client::find($validated['client_id']);
        $client->solde_dette = max(0, $client->solde_dette - $validated['montant_paye']);
        $client->save();

        return redirect()->route('paiements.show', $paiement)
            ->with('success', 'Paiement enregistré avec succès');
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['transaction', 'client', 'administrateur']);
        return view('paiements.show', compact('paiement'));
    }

    public function edit(Paiement $paiement)
    {
        $transactions = Transaction::where('statut_paiement', '!=', 'paye')
            ->with(['client', 'typeBouteille'])
            ->get();
        $clients = Client::where('statut', 'actif')->get();
        return view('paiements.edit', compact('paiement', 'transactions', 'clients'));
    }

    public function update(Request $request, Paiement $paiement)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'client_id' => 'required|exists:clients,id',
            'montant_paye' => 'required|numeric|min:0.01',
            'mode_paiement' => 'required|in:especes,cheque,virement,carte',
            'reference_cheque' => 'nullable|string|max:255',
            'reference_virement' => 'nullable|string|max:255',
            'reference_carte' => 'nullable|string|max:255',
            'statut' => 'required|in:recu,confirme,refuse',
            'notes' => 'nullable|string|max:500',
            'date_paiement' => 'required|date',
        ]);

        $paiement->update($validated);

        return redirect()->route('paiements.show', $paiement)
            ->with('success', 'Paiement mis à jour avec succès');
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        return redirect()->route('paiements.index')
            ->with('success', 'Paiement supprimé avec succès');
    }

    public function byClient(Client $client)
    {
        $paiements = $client->paiements()
            ->with(['transaction', 'administrateur'])
            ->latest()
            ->paginate(15);
        return view('paiements.by-client', compact('client', 'paiements'));
    }
}
