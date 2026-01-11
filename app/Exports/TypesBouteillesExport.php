<?php

namespace App\Exports;

use App\Models\TypeBouteille;
use Illuminate\Support\Collection;

class TypesBouteillesExport extends BaseExport
{
    public function headings(): array
    {
        return ['Marque', 'Taille', 'Prix Vente', 'Prix Recharge', 'Seuil Alerte', 'Statut', 'Créé le'];
    }

    public function collection(): Collection
    {
        return TypeBouteille::with('marque')->get()->map(function ($type) {
            return [
                $type->marque->nom,
                $type->taille . 'L',
                number_format($type->prix_vente, 2, '.', ''),
                number_format($type->prix_recharge, 2, '.', ''),
                $type->seuil_alerte,
                ucfirst($type->statut),
                $type->created_at->format('d/m/Y H:i'),
            ];
        });
    }
}
