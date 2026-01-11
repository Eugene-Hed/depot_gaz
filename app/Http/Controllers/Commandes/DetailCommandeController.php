<?php

namespace App\Http\Controllers\Commandes;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\DetailCommande;
use App\Models\TypeBouteille;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailCommandeController extends Controller
{
    public function index(Commande $commande)
    {
        $details = $commande->details()->with(['typeBouteille'])->get();
        return view('commandes.details.index', compact('commande', 'details'));
    }

    public function create(Commande $commande)
    {
        $types = TypeBouteille::all();
        return view('commandes.details.create', compact('commande', 'types'));
    }

    public function store(Request $request, Commande $commande)
    {
        $validated = $request->validate([
            'type_bouteille_id' => 'required|exists:types_bouteilles,id',
            'quantite_commandee' => 'required|integer|min:1',
            'quantite_livree' => 'required|integer|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'statut_ligne' => 'required|in:en_attente,partielle,livree,annulee',
        ]);

        // Calculer le montant de la ligne
        $montantLigne = $validated['quantite_commandee'] * $validated['prix_unitaire'];

        $detail = $commande->details()->create([
            ...$validated,
            'montant_ligne' => $montantLigne,
        ]);

        // Mettre à jour le montant total de la commande
        $this->updateCommandeTotal($commande);

        return redirect()->route('commandes.details.index', $commande)
            ->with('success', 'Détail de commande ajouté avec succès');
    }

    public function edit(Commande $commande, DetailCommande $detail)
    {
        $types = TypeBouteille::all();
        return view('commandes.details.edit', compact('commande', 'detail', 'types'));
    }

    public function update(Request $request, Commande $commande, DetailCommande $detail)
    {
        $validated = $request->validate([
            'type_bouteille_id' => 'required|exists:types_bouteilles,id',
            'quantite_commandee' => 'required|integer|min:1',
            'quantite_livree' => 'required|integer|min:0',
            'prix_unitaire' => 'required|numeric|min:0',
            'statut_ligne' => 'required|in:en_attente,partielle,livree,annulee',
        ]);

        // Calculer le montant de la ligne
        $montantLigne = $validated['quantite_commandee'] * $validated['prix_unitaire'];

        $detail->update([
            ...$validated,
            'montant_ligne' => $montantLigne,
        ]);

        // Mettre à jour le montant total de la commande
        $this->updateCommandeTotal($commande);

        return redirect()->route('commandes.details.index', $commande)
            ->with('success', 'Détail de commande mis à jour avec succès');
    }

    public function destroy(Commande $commande, DetailCommande $detail)
    {
        $detail->delete();
        $this->updateCommandeTotal($commande);

        return redirect()->route('commandes.details.index', $commande)
            ->with('success', 'Détail de commande supprimé avec succès');
    }

    public function updateLivraison(Request $request, Commande $commande, DetailCommande $detail)
    {
        $validated = $request->validate([
            'quantite_livree' => 'required|integer|min:0|max:' . $detail->quantite_commandee,
        ]);

        $quantiteRestante = $detail->quantite_commandee - $validated['quantite_livree'];

        $detail->update([
            'quantite_livree' => $validated['quantite_livree'],
            'statut_ligne' => $validated['quantite_livree'] == 0 ? 'en_attente' : 
                             ($validated['quantite_livree'] == $detail->quantite_commandee ? 'livree' : 'partielle'),
        ]);

        // Vérifier si toute la commande est livrée
        $tousLivres = $commande->details()
            ->where('statut_ligne', '!=', 'livree')
            ->count() === 0;

        if ($tousLivres) {
            $commande->update(['statut' => 'livree']);
        }

        $this->updateCommandeTotal($commande);

        return redirect()->route('commandes.details.index', $commande)
            ->with('success', 'Livraison mise à jour avec succès');
    }

    private function updateCommandeTotal(Commande $commande)
    {
        $montantHT = $commande->details()->sum('montant_ligne');
        $montantTaxes = $montantHT * 0.18; // 18% TVA
        $montantTotal = $montantHT + $montantTaxes + ($commande->cout_transport ?? 0);

        $commande->update([
            'montant_ht' => $montantHT,
            'montant_taxes' => $montantTaxes,
            'montant_total' => $montantTotal,
        ]);
    }
}
