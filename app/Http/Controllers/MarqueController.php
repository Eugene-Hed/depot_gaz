<?php

namespace App\Http\Controllers;

use App\Models\Marque;
use Illuminate\Http\Request;

class MarqueController extends Controller
{
    /**
     * Afficher la liste des marques
     */
    public function index()
    {
        $marques = Marque::paginate(20);
        return view('marques.index', compact('marques'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('marques.create');
    }

    /**
     * Enregistrer une nouvelle marque
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:marques',
            'statut' => 'required|in:actif,inactif',
        ]);

        try {
            Marque::create($validated);

            return redirect()
                ->route('marques.index')
                ->with('success', 'Marque créée avec succès');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Marque $marque)
    {
        return view('marques.edit', compact('marque'));
    }

    /**
     * Mettre à jour une marque
     */
    public function update(Request $request, Marque $marque)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:marques,nom,' . $marque->id,
            'statut' => 'required|in:actif,inactif',
        ]);

        try {
            $marque->update($validated);

            return redirect()
                ->route('marques.index')
                ->with('success', 'Marque mise à jour avec succès');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Supprimer une marque
     */
    public function destroy(Marque $marque)
    {
        try {
            $marque->delete();

            return redirect()
                ->route('marques.index')
                ->with('success', 'Marque supprimée avec succès');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Impossible de supprimer cette marque']);
        }
    }
}
