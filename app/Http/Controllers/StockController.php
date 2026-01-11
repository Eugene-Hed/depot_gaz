<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\TypeBouteille;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Afficher la liste des stocks
     */
    public function index()
    {
        // Récupérer les stocks avec relations
        $stocks = Stock::with('typeBouteille.marque')
            ->paginate(20);

        // Ajouter les statuts et informations enrichies
        $stocks->getCollection()->transform(function ($stock) {
            $stock->status = ($stock->quantite_pleine < $stock->typeBouteille->seuil_alerte) 
                ? 'rupture' 
                : 'ok';
            
            // Ajouter le pourcentage de disponibilité
            $seuil = max($stock->typeBouteille->seuil_alerte, 1);
            $stock->pourcentage = min(($stock->quantite_pleine / $seuil) * 100, 100);
            
            return $stock;
        });

        return view('stocks.index', compact('stocks'));
    }

    /**
     * Afficher le formulaire de création de mouvement
     */
    public function create()
    {
        $typesBouteilles = TypeBouteille::with('marque')
            ->get();

        return view('stocks.create', compact('typesBouteilles'));
    }

    /**
     * Enregistrer un nouveau mouvement de stock
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_type_bouteille' => 'required|exists:types_bouteilles,id',
            'type_mouvement' => 'required|in:entree,sortie,ajustement',
            'quantite_pleine' => 'required|integer|min:0',
            'quantite_vide' => 'required|integer|min:0',
            'motif' => 'nullable|string|max:100',
            'commentaire' => 'nullable|string|max:500',
        ]);

        try {
            // Récupérer ou créer le stock
            $stock = Stock::firstOrCreate(
                ['type_bouteille_id' => $validated['id_type_bouteille']],
                [
                    'quantite_pleine' => 0,
                    'quantite_vide' => 0,
                ]
            );

            // Calculer les nouvelles quantités selon le type de mouvement
            $nouvellePleine = $stock->quantite_pleine;
            $nouvelleVide = $stock->quantite_vide;

            if ($validated['type_mouvement'] === 'entree') {
                $nouvellePleine += $validated['quantite_pleine'];
                $nouvelleVide += $validated['quantite_vide'];
            } elseif ($validated['type_mouvement'] === 'sortie') {
                // Vérifier la disponibilité
                if ($stock->quantite_pleine < $validated['quantite_pleine']) {
                    return back()
                        ->withInput()
                        ->withErrors(['quantite_pleine' => 'Stock insuffisant']);
                }

                $nouvellePleine -= $validated['quantite_pleine'];
                $nouvelleVide -= $validated['quantite_vide'];
            } elseif ($validated['type_mouvement'] === 'ajustement') {
                // Remplacement complet des valeurs
                $nouvellePleine = $validated['quantite_pleine'];
                $nouvelleVide = $validated['quantite_vide'];
            }

            // Mettre à jour le stock
            $stock->update([
                'quantite_pleine' => max(0, $nouvellePleine),
                'quantite_vide' => max(0, $nouvelleVide),
            ]);

            // Enregistrer le mouvement dans l'historique
            MouvementStock::create([
                'stock_id' => $stock->id,
                'type_mouvement' => $validated['type_mouvement'],
                'quantite_pleine' => $validated['quantite_pleine'],
                'quantite_vide' => $validated['quantite_vide'],
                'motif' => $validated['motif'] ?? null,
                'commentaire' => $validated['commentaire'] ?? null,
                'administrateur_id' => Auth::id(),
            ]);

            return redirect()
                ->route('stocks.index')
                ->with('success', 'Mouvement enregistré avec succès');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Stock $stock)
    {
        $stock->load('typeBouteille.marque');

        return view('stocks.edit', compact('stock'));
    }

    /**
     * Mettre à jour les quantités d'un stock
     */
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantite_pleine' => 'required|integer|min:0',
            'quantite_vide' => 'required|integer|min:0',
            'commentaire' => 'nullable|string|max:500',
        ]);

        try {
            // Calculer les différences pour l'enregistrement du mouvement
            $diffPleine = $validated['quantite_pleine'] - $stock->quantite_pleine;
            $diffVide = $validated['quantite_vide'] - $stock->quantite_vide;

            // Mettre à jour le stock
            $stock->update([
                'quantite_pleine' => $validated['quantite_pleine'],
                'quantite_vide' => $validated['quantite_vide'],
            ]);

            // Enregistrer comme ajustement
            MouvementStock::create([
                'stock_id' => $stock->id,
                'type_mouvement' => 'ajustement',
                'quantite_pleine' => abs($diffPleine),
                'quantite_vide' => abs($diffVide),
                'motif' => $diffPleine < 0 ? 'diminution' : 'augmentation',
                'commentaire' => $validated['commentaire'] ?? 'Ajustement manuel',
                'administrateur_id' => Auth::id(),
            ]);

            return redirect()
                ->route('stocks.index')
                ->with('success', 'Stock ajusté avec succès');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Erreur lors de la mise à jour: ' . $e->getMessage()]);
        }
    }

    /**
     * Afficher les détails d'un stock
     */
    public function show(Stock $stock)
    {
        $stock->load('typeBouteille.marque', 'mouvements');

        // Charger les 20 derniers mouvements
        $mouvements = $stock->mouvements()
            ->with('administrateur')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('stocks.show', compact('stock', 'mouvements'));
    }

    /**
     * Afficher l'historique des mouvements
     */
    public function historique()
    {
        $mouvements = MouvementStock::with(['stock.typeBouteille', 'administrateur'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('stocks.historique', compact('mouvements'));
    }

    /**
     * Récupérer les alertes de rupture
     */
    public function alertes()
    {
        $stocks = Stock::with('typeBouteille')
            ->get()
            ->filter(function ($stock) {
                return $stock->quantite_pleine < $stock->typeBouteille->seuil_alerte;
            });

        return response()->json([
            'count' => $stocks->count(),
            'stocks' => $stocks->map(function ($stock) {
                return [
                    'id' => $stock->id,
                    'type' => $stock->typeBouteille->nom,
                    'marque' => $stock->typeBouteille->marque->nom,
                    'quantite' => $stock->quantite_pleine,
                    'seuil' => $stock->typeBouteille->seuil_alerte,
                ];
            }),
        ]);
    }

    /**
     * Exporter les stocks en JSON
     */
    public function export()
    {
        $stocks = Stock::with('typeBouteille.marque')
            ->get()
            ->map(function ($stock) {
                return [
                    'id' => $stock->id,
                    'type_bouteille' => $stock->typeBouteille->nom,
                    'marque' => $stock->typeBouteille->marque->nom,
                    'quantite_pleine' => $stock->quantite_pleine,
                    'quantite_vide' => $stock->quantite_vide,
                    'seuil_alerte' => $stock->typeBouteille->seuil_alerte,
                    'status' => $stock->quantite_pleine < $stock->typeBouteille->seuil_alerte 
                        ? 'RUPTURE' 
                        : 'OK',
                ];
            });

        return response()->json($stocks);
    }
}
