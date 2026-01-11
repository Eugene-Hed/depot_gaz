<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Collection;

class ClientsExport extends BaseExport
{
    public function headings(): array
    {
        return ['ID', 'Nom', 'Téléphone', 'Email', 'Adresse', 'Solde Crédit', 'Solde Débit', 'Statut', 'Créé le'];
    }

    public function collection(): Collection
    {
        return Client::all()->map(function ($client) {
            return [
                $client->id,
                $client->nom,
                $client->telephone,
                $client->email ?? '',
                $client->adresse ?? '',
                number_format($client->solde_credit, 2, '.', ''),
                number_format($client->solde_debit, 2, '.', ''),
                ucfirst($client->statut),
                $client->created_at->format('d/m/Y H:i'),
            ];
        });
    }
}
