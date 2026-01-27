<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport Stocks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 11px;
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
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .header-info {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alerte {
            color: #dc3545;
            font-weight: bold;
        }
        .ok {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Rapport Stocks</h1>
    
    <div class="header-info">
        <strong>Date d'export:</strong> {{ now()->format('d/m/Y à H:i') }}<br>
        <strong>Nombre total:</strong> {{ count($records) }} articles
    </div>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Marque</th>
                <th>Taille</th>
                <th>Quantité Pleine</th>
                <th>Quantité Vide</th>
                <th>Seuil Alerte</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $stock)
                <tr>
                    <td>{{ $stock->typeBouteille->marque->nom }} {{ $stock->typeBouteille->taille }}L</td>
                    <td>{{ $stock->typeBouteille->marque->nom }}</td>
                    <td>{{ $stock->typeBouteille->taille }}L</td>
                    <td style="text-align: right;">{{ $stock->quantite_pleine }}</td>
                    <td style="text-align: right;">{{ $stock->quantite_vide }}</td>
                    <td style="text-align: right;">{{ $stock->typeBouteille->seuil_alerte }}</td>
                    <td>
                        @if($stock->quantite_pleine < $stock->typeBouteille->seuil_alerte)
                            <span class="alerte">⚠ ALERTE</span>
                        @else
                            <span class="ok">✓ OK</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #999;">Aucune donnée</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Rapport généré automatiquement par Depot Gaz - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
