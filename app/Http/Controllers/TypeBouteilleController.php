<?php

namespace App\Http\Controllers;

use App\Models\TypeBouteille;
use App\Models\Marque;
use Illuminate\Http\Request;

class TypeBouteilleController extends Controller
{
    /**
     * Afficher la liste des types de bouteilles
     */
    public function index()
    {
        $types = TypeBouteille::with('marque')->paginate(20);
        return view('types_bouteilles.index', compact('types'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $marques = Marque::where('statut', 'actif')->get();
        return view('types_bouteilles.create', compact('marques'));
    }

    /**
     * Enregistrer un nouveau type
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'taille' => 'required|string|max:100',
            'id_marque' => 'required|exists:marques,id',
            'prix_vente' => 'required|numeric|min:0',
            'prix_consigne' => 'required|numeric|min:0',
            'prix_recharge' => 'required|numeric|min:0',
            'seuil_alerte' => 'required|integer|min:0',
            'statut' => 'required|in:actif,inactif',
        ]);

        try {
            $validated['marque_id'] = $validated['id_marque'];
            unset($validated['id_marque']);

            TypeBouteille::create($validated);

            return redirect()
                ->route('types-bouteilles.index')
                ->with('success', 'Type de bouteille créé avec succès');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(TypeBouteille $typeBouteille)
    {
        $marques = Marque::where('statut', 'actif')->get();
        return view('types_bouteilles.edit', compact('typeBouteille', 'marques'));
    }

    /**
     * Mettre à jour un type
     */
    public function update(Request $request, TypeBouteille $typeBouteille)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'taille' => 'required|string|max:100',
            'id_marque' => 'required|exists:marques,id',
            'prix_vente' => 'required|numeric|min:0',
            'prix_consigne' => 'required|numeric|min:0',
            'prix_recharge' => 'required|numeric|min:0',
            'seuil_alerte' => 'required|integer|min:0',
            'statut' => 'required|in:actif,inactif',
        ]);

        try {
            $validated['marque_id'] = $validated['id_marque'];
            unset($validated['id_marque']);

            $typeBouteille->update($validated);

            return redirect()
                ->route('types-bouteilles.index')
                ->with('success', 'Type de bouteille mis à jour avec succès');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Supprimer un type
     */
    public function destroy(TypeBouteille $typeBouteille)
    {
        try {
            $typeBouteille->delete();

            return redirect()
                ->route('types-bouteilles.index')
                ->with('success', 'Type de bouteille supprimé avec succès');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Impossible de supprimer ce type']);
        }
    }
}
