<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Afficher la liste des fournisseurs
     */
    public function index()
    {
        $fournisseurs = Fournisseur::paginate(20);
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('fournisseurs.create');
    }

    /**
     * Enregistrer un nouveau fournisseur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:fournisseurs',
            'telephone' => 'required|string|max:20|unique:fournisseurs',
            'email' => 'nullable|email|unique:fournisseurs',
            'adresse' => 'nullable|string|max:500',
            'statut' => 'required|in:actif,inactif',
        ]);

        try {
            // Générer un code fournisseur unique
            $code = 'FOUR-' . strtoupper(substr(md5(uniqid()), 0, 8));
            $validated['code_fournisseur'] = $code;

            Fournisseur::create($validated);

            return redirect()
                ->route('fournisseurs.index')
                ->with('success', 'Fournisseur créé avec succès');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    /**
     * Mettre à jour un fournisseur
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:fournisseurs,nom,' . $fournisseur->id,
            'telephone' => 'required|string|max:20|unique:fournisseurs,telephone,' . $fournisseur->id,
            'email' => 'nullable|email|unique:fournisseurs,email,' . $fournisseur->id,
            'adresse' => 'nullable|string|max:500',
            'statut' => 'required|in:actif,inactif',
        ]);

        try {
            $fournisseur->update($validated);

            return redirect()
                ->route('fournisseurs.index')
                ->with('success', 'Fournisseur mis à jour avec succès');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Supprimer un fournisseur
     */
    public function destroy(Fournisseur $fournisseur)
    {
        try {
            $fournisseur->delete();

            return redirect()
                ->route('fournisseurs.index')
                ->with('success', 'Fournisseur supprimé avec succès');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Impossible de supprimer ce fournisseur']);
        }
    }
}
