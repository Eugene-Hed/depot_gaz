<?php

namespace App\Exports;

use App\Models\Fournisseur;
use Illuminate\Support\Collection;

class FournisseursExport extends BaseExport
{
    public function headings(): array
    {
        return ['Code', 'Nom', 'Téléphone', 'Email', 'Adresse', 'Statut', 'Créé le'];
    }

    public function collection(): Collection
    {
        return Fournisseur::all()->map(function ($fournisseur) {
            return [
                $fournisseur->code_fournisseur,
                $fournisseur->nom,
                $fournisseur->telephone,
                $fournisseur->email ?? '',
                $fournisseur->adresse ?? '',
                ucfirst($fournisseur->statut),
                $fournisseur->created_at->format('d/m/Y H:i'),
            ];
        });
    }
}
