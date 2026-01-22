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
        $types = TypeBouteille::with(['marque', 'stock'])->latest()->paginate(20);
        
        // Calculer les statistiques une seule fois
        $stats = [
            'typesActifs' => TypeBouteille::where('statut', 'actif')->count(),
            'prixMoyen' => TypeBouteille::avg('prix_vente'),
            'alertesStock' => TypeBouteille::whereHas('stock', function($q) {
                $q->whereRaw('quantite_pleine < types_bouteilles.seuil_alerte');
            })->count(),
        ];
        
        return view('types_bouteilles.index', compact('types', 'stats'));
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
        // Debug complet
        \Log::info('=== DÉBUT STORE ===');
        \Log::info('Données reçues:', $request->all());
        \Log::info('Fichiers:', $request->allFiles());
        \Log::info('hasFile image:', [$request->hasFile('image')]);
        
        $validated = $request->validate([
            'taille' => 'required|string|max:100',
            'id_marque' => 'required|exists:marques,id',
            'prix_vente' => 'required|numeric|min:0',
            'prix_recharge' => 'required|numeric|min:0',
            'seuil_alerte' => 'required|integer|min:0',
            'statut' => 'required|in:actif,inactif',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        \Log::info('Validation OK');

        try {
            $validated['marque_id'] = $validated['id_marque'];
            unset($validated['id_marque']);

            // Upload de l'image si présente
            if ($request->hasFile('image')) {
                \Log::info('Image détectée');
                $image = $request->file('image');
                \Log::info('Infos image:', [
                    'original' => $image->getClientOriginalName(),
                    'mime' => $image->getMimeType(),
                    'size' => $image->getSize(),
                    'valid' => $image->isValid()
                ]);
                
                $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
                
                // Stocker l'image dans le disk public
                $path = $image->storeAs('bouteilles', $imageName, 'public');
                
                \Log::info('Après storeAs:', ['path' => $path]);
                
                // Vérifier que le fichier a bien été stocké
                if (!$path) {
                    throw new \Exception('Erreur lors de l\'enregistrement de l\'image');
                }
                
                // Vérifier l'existence
                $exists = \Storage::disk('public')->exists('bouteilles/' . $imageName);
                \Log::info('Fichier existe:', ['exists' => $exists]);
                
                $validated['image'] = $imageName;
                
                \Log::info('Image uploadée: ' . $imageName . ' - Path: ' . $path);
            } else {
                \Log::info('Aucune image uploadée');
            }

            $typeBouteille = TypeBouteille::create($validated);
            
            \Log::info('Type créé avec succès', ['id' => $typeBouteille->id, 'image' => $typeBouteille->image]);

            return redirect()
                ->route('types-bouteilles.index')
                ->with('success', 'Type de bouteille créé avec succès');
        } catch (\Exception $e) {
            \Log::error('Erreur création type bouteille: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
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
            'taille' => 'required|string|max:100',
            'id_marque' => 'required|exists:marques,id',
            'prix_vente' => 'required|numeric|min:0',
            'prix_recharge' => 'required|numeric|min:0',
            'seuil_alerte' => 'required|integer|min:0',
            'statut' => 'required|in:actif,inactif',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $validated['marque_id'] = $validated['id_marque'];
            unset($validated['id_marque']);

            // Retirer le champ image des données validées pour éviter d'écraser l'ancienne
            unset($validated['image']);

            // Upload de la nouvelle image si présente
            if ($request->hasFile('image')) {
                \Log::info('Nouvelle image détectée pour le type ' . $typeBouteille->id);
                
                // Supprimer l'ancienne image si elle existe
                if ($typeBouteille->image) {
                    $oldPath = 'bouteilles/' . $typeBouteille->image;
                    if (\Storage::disk('public')->exists($oldPath)) {
                        \Storage::disk('public')->delete($oldPath);
                        \Log::info('Ancienne image supprimée: ' . $oldPath);
                    }
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
                
                // Stocker la nouvelle image dans le disk public
                $path = $image->storeAs('bouteilles', $imageName, 'public');
                
                if (!$path) {
                    throw new \Exception('Erreur lors de l\'enregistrement de l\'image');
                }
                
                $validated['image'] = $imageName;
                \Log::info('Nouvelle image enregistrée: ' . $imageName);
            } else {
                \Log::info('Pas de nouvelle image pour le type ' . $typeBouteille->id);
            }

            $typeBouteille->update($validated);
            
            \Log::info('Type mis à jour avec succès', ['id' => $typeBouteille->id, 'image' => $typeBouteille->image]);

            return redirect()
                ->route('types-bouteilles.index')
                ->with('success', 'Type de bouteille mis à jour avec succès');
        } catch (\Exception $e) {
            \Log::error('Erreur mise à jour type bouteille: ' . $e->getMessage());
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
            // Supprimer l'image si elle existe
            if ($typeBouteille->image) {
                \Storage::disk('public')->delete('bouteilles/' . $typeBouteille->image);
            }
            
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
