<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport Clients</title>
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
    <h1>Rapport Clients</h1>
    
    <div class="header-info">
        <strong>Date d'export:</strong> {{ now()->format('d/m/Y à H:i') }}<br>
        <strong>Nombre total:</strong> {{ count($records) }} clients
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Solde Crédit</th>
                <th>Solde Débit</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->nom }}</td>
                    <td>{{ $client->telephone }}</td>
                    <td>{{ $client->email ?? '-' }}</td>
                    <td>{{ $client->adresse ?? '-' }}</td>
                    <td>{{ number_format($client->solde_credit, 2, ',', ' ') }}</td>
                    <td>{{ number_format($client->solde_debit, 2, ',', ' ') }}</td>
                    <td>{{ ucfirst($client->statut) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #999;">Aucune donnée</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Rapport généré automatiquement par Depot Gaz - {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
