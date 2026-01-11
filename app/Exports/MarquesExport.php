<?php

namespace App\Exports;

use App\Models\Marque;
use Illuminate\Support\Collection;

class MarquesExport extends BaseExport
{
    public function headings(): array
    {
        return ['Nom', 'Types', 'Statut', 'Description', 'Créé le'];
    }

    public function collection(): Collection
    {
        return Marque::withCount('typesBouteilles')->get()->map(function ($marque) {
            return [
                $marque->nom,
                $marque->types_bouteilles_count,
                ucfirst($marque->statut),
                $marque->description ?? '',
                $marque->created_at->format('d/m/Y H:i'),
            ];
        });
    }
}
