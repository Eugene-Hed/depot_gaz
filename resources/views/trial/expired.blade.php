<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Période de Test Expirée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .expired-container {
            background: white;
            padding: 50px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 550px;
            animation: slideDown 0.5s ease-out;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .expired-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 25px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 700;
        }
        .lead {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }
        .alert {
            margin: 30px 0;
            border-left: 5px solid #0d6efd;
            background-color: #f8f9ff;
        }
        .alert ul {
            margin: 0;
            padding-left: 20px;
        }
        .alert li {
            margin: 8px 0;
            color: #444;
        }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .date-highlight {
            color: #dc3545;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .footer-text {
            color: #999;
            font-size: 0.9rem;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="expired-container">
        <div class="expired-icon">
            <i class="bi bi-exclamation-circle-fill"></i>
        </div>
        
        <h1>Période de Test Expirée</h1>
        
        <p class="lead">
            Votre période d'essai a expiré le <span class="date-highlight">{{ $expired_on->format('d/m/Y à H:i') }}</span>
        </p>

        <div class="info-box">
            <p class="mb-0">
                <strong>Résumé de votre test:</strong><br>
                Durée: <strong>30 jours</strong><br>
                Date d'expiration: <strong>{{ $expired_on->format('d/m/Y') }}</strong>
            </p>
        </div>

        <div class="alert alert-info" role="alert">
            <p class="mb-3"><strong>Pour continuer à utiliser <span style="color: #667eea;">Dépôt GAZ</span>:</strong></p>
            <ul class="list-unstyled">
                <li><i class="bi bi-check-circle text-success"></i> Obtenir une licence de production</li>
                <li><i class="bi bi-arrow-repeat text-primary"></i> Réinstaller l'application pour une nouvelle période de test</li>
                <li><i class="bi bi-question-circle text-warning"></i> Contacter le support pour plus d'informations</li>
            </ul>
        </div>

        <div style="background: white; border: 2px solid #667eea; border-radius: 8px; padding: 15px; margin: 25px 0;">
            <p class="small text-muted mb-2">Contact Support:</p>
            <p class="mb-0">
                <strong>GitHub:</strong> <a href="https://github.com/Eugene-Hed" target="_blank" class="text-decoration-none">Eugene-Hed</a>
            </p>
        </div>

        <p class="footer-text">
            <strong>Dépôt GAZ</strong> v1.0<br>
            Système de Gestion de Stock pour Dépôts de Gaz
        </p>
    </div>
</body>
</html>
