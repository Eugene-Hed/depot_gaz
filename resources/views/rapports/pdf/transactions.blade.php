<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport Transactions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 10px;
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #6366f1;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #6366f1;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header-info {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 11px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .type-echange-simple { background-color: #28a745; color: white; }
        .type-echange-type { background-color: #fd7e14; color: white; }
        .type-achat-simple { background-color: #007bff; color: white; }
        .type-echange-differe { background-color: #17a2b8; color: white; }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #e8f0ff;
            border-left: 4px solid #6366f1;
            font-size: 11px;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Rapport des Transactions</h1>
    
    <div class="header-info">
        <strong>Date d'export:</strong> {{ now()->format('d/m/Y à H:i') }}<br>
        <strong>Nombre total:</strong> {{ count($records) }} transactions<br>
        <strong>Montant total:</strong> {{ number_format($records->sum('montant_net'), 2, ',', ' ') }} DA
    </div>

    <table>
        <thead>
            <tr>
                <th>N° Transaction</th>
                <th>Client</th>
                <th>Type</th>
                <th>Bouteille</th>
                <th style="text-align: center;">Qty</th>
                <th class="text-right">Montant Total</th>
                <th class="text-right">Consigne</th>
                <th class="text-right">Réduction</th>
                <th class="text-right">Montant Net</th>
                <th>Paiement</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $transaction)
                <tr>
                    <td><strong>{{ $transaction->numero_transaction ?? '-' }}</strong></td>
                    <td>{{ $transaction->client?->nom ?? 'Client Anonyme #' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <span class="badge type-{{ str_replace('_', '-', $transaction->type) }}">
                            {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                        </span>
                    </td>
                    <td>{{ $transaction->typeBouteille?->nom ?? '-' }}</td>
                    <td class="text-center">{{ $transaction->quantite ?? '-' }}</td>
                    <td class="text-right">{{ number_format($transaction->montant_total ?? 0, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($transaction->consigne_montant ?? 0, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($transaction->montant_reduction ?? 0, 2, ',', ' ') }}</td>
                    <td class="text-right"><strong>{{ number_format($transaction->montant_net ?? 0, 2, ',', ' ') }}</strong></td>
                    <td>{{ ucfirst($transaction->mode_paiement ?? '-') }}</td>
                    <td>
                        <span class="badge {{ $transaction->statut_paiement === 'payé' ? 'type-achat-simple' : 'type-echange-type' }}">
                            {{ ucfirst($transaction->statut_paiement ?? '-') }}
                        </span>
                    </td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center" style="color: #999;">Aucune transaction trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <strong>Résumé:</strong> {{ count($records) }} transaction(s) | Montant Total: {{ number_format($records->sum('montant_net'), 2, ',', ' ') }} DA | Consignes: {{ number_format($records->sum('consigne_montant'), 2, ',', ' ') }} DA | Réductions: {{ number_format($records->sum('montant_reduction'), 2, ',', ' ') }} DA
    </div>
</body>
</html>
