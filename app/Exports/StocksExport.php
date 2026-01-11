<?php

namespace App\Exports;

use App\Models\Stock;
use Illuminate\Support\Collection;

class StocksExport extends BaseExport
{
    public function headings(): array
    {
        return ['Type', 'Marque', 'Taille', 'Quantité Pleine', 'Quantité Vide', 'Seuil Alerte', 'Statut'];
    }

    public function collection(): Collection
    {
        return Stock::with(['typeBouteille', 'typeBouteille.marque'])->get()->map(function ($stock) {
            return [
                $stock->typeBouteille->marque->nom . ' ' . $stock->typeBouteille->taille . 'L',
                $stock->typeBouteille->marque->nom,
                $stock->typeBouteille->taille,
                $stock->quantite_pleine,
                $stock->quantite_vide,
                $stock->typeBouteille->seuil_alerte,
                $stock->quantite_pleine < $stock->typeBouteille->seuil_alerte ? 'ALERTE' : 'OK',
            ];
        });
    }
}
