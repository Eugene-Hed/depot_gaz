<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use App\Models\TypeBouteille;
use Illuminate\Http\Request;

class MouvementStockController extends Controller
{
    /**
     * Affiche l'historique des mouvements de stock (Audit)
     */
    public function index(Request $request)
    {
        $query = MouvementStock::with(['stock.typeBouteille.marque', 'administrateur']);

        // Filtre par type de bouteille
        if ($request->filled('type_bouteille_id')) {
            $query->whereHas('stock', function ($q) use ($request) {
                $q->where('type_bouteille_id', $request->type_bouteille_id);
            });
        }

        // Filtre par type de mouvement
        if ($request->filled('type_mouvement')) {
            $query->where('type_mouvement', $request->type_mouvement);
        }

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $mouvements = $query->latest()->paginate(20);
        $typesBouteilles = TypeBouteille::with('marque')->orderBy('nom')->get();

        return view('stocks.mouvements', compact('mouvements', 'typesBouteilles'));
    }
}
