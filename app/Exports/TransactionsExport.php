<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Collection;

class TransactionsExport extends BaseExport
{
    public function headings(): array
    {
        return [
            'N° Transaction',
            'Client',
            'Type',
            'Bouteille',
            'Quantité',
            'Prix Unitaire',
            'Montant Total',
            'Consigne',
            'Réduction',
            'Montant Net',
            'Mode Paiement',
            'Statut Paiement',
            'Limite Retour',
            'Commentaire',
            'Créé par',
            'Date Transaction'
        ];
    }

    public function collection(): Collection
    {
        return Transaction::with(['client', 'typeBouteille', 'administrateur'])
            ->latest()
            ->get()
            ->map(function ($transaction) {
                // Générer un nom anonyme si pas de client
                $clientName = $transaction->client?->nom ?? 'Client Anonyme #' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT);
                
                return [
                    $transaction->numero_transaction ?? '-',
                    $clientName,
                    ucfirst(str_replace('_', ' ', $transaction->type)),
                    $transaction->typeBouteille?->nom ?? '-',
                    $transaction->quantite ?? '-',
                    number_format($transaction->prix_unitaire ?? 0, 2, '.', ''),
                    number_format($transaction->montant_total ?? 0, 2, '.', ''),
                    number_format($transaction->consigne_montant ?? 0, 2, '.', ''),
                    number_format($transaction->montant_reduction ?? 0, 2, '.', ''),
                    number_format($transaction->montant_net ?? 0, 2, '.', ''),
                    ucfirst($transaction->mode_paiement ?? '-'),
                    ucfirst($transaction->statut_paiement ?? '-'),
                    $transaction->date_limite_retour?->format('d/m/Y') ?? '-',
                    $transaction->commentaire ?? '-',
                    $transaction->administrateur?->name ?? '-',
                    $transaction->created_at->format('d/m/Y H:i'),
                ];
            });
    }
}
