<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport Marques</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
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
    </style>
</head>
<body>
    <h1>Rapport Marques</h1>
    
    <div class="header-info">
        <strong>Date d'export:</strong> {{ now()->format('d/m/Y à H:i') }}<br>
        <strong>Nombre total:</strong> {{ count($records) }} marques
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Types</th>
                <th>Statut</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $marque)
                <tr>
                    <td>{{ $marque->nom }}</td>
                    <td style="text-align: center;">{{ $marque->types_bouteilles_count }}</td>
                    <td>{{ ucfirst($marque->statut) }}</td>
                    <td>{{ $marque->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #999;">Aucune donnée</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Rapport généré automatiquement par Depot Gaz - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
