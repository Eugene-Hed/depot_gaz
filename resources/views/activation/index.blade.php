<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0f172a;
            --slate: #475569;
            --light-navy: #f1f5f9;
        }
        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
            color: var(--navy);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .auth-container {
            width: 100%;
            max-width: 480px;
            margin: auto;
            padding: 20px;
        }
        .card-activation {
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .ls-wide { letter-spacing: 0.05em; }
        .font-2xs { font-size: 0.7rem; }
        .bg-navy { background-color: var(--navy) !important; }
        .text-navy { color: var(--navy) !important; }
        .btn-navy {
            background-color: var(--navy);
            color: white;
            transition: all 0.2s;
        }
        .btn-navy:hover {
            background-color: #1e293b;
            color: white;
            transform: translateY(-1px);
        }
        .install-id-box {
            background-color: var(--light-navy);
            border: 1px dashed #cbd5e1;
            padding: 15px;
            border-radius: 12px;
            font-family: 'Monaco', 'Consolas', monospace;
            font-weight: 700;
            color: var(--navy);
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="text-center mb-5">
            <h1 class="h4 fw-extrabold text-navy text-uppercase ls-wide mb-1">Dépôt Gaz <span class="text-secondary fw-normal">| Management</span></h1>
            <p class="text-muted small">Activation de la licence d'exploitation</p>
        </div>

        <div class="card-activation">
            <div class="p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="avatar-circle bg-warning bg-opacity-10 text-warning mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; border-radius: 20px;">
                        <i class="bi bi-shield-lock-fill fs-2"></i>
                    </div>
                    @if($isTrialActive)
                        <h5 class="fw-bold text-navy mb-1">Version d'Évaluation</h5>
                        <p class="text-muted small">Il vous reste <span class="fw-bold text-warning">{{ $daysRemaining }} jours</span> d'essai</p>
                    @else
                        <h5 class="fw-bold text-danger mb-1">Licence Expirée</h5>
                        <p class="text-muted small">Veuillez activer votre copie du logiciel</p>
                    @endif
                </div>

                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 small">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4 text-center">
                    <label class="font-2xs text-muted fw-bold text-uppercase ls-wide mb-2 d-block">ID Installation (Trace unique)</label>
                    <div class="install-id-box mb-2" id="installID">{{ $installID }}</div>
                    <button class="btn btn-link btn-sm text-navy text-decoration-none fw-bold font-2xs text-uppercase" onclick="copyID()">
                        <i class="bi bi-clipboard me-1"></i> Copier l'ID
                    </button>
                    <p class="font-2xs text-secondary mt-3 mb-0">Communiquez cet ID à l'administrateur <br>pour générer votre clé d'activation.</p>
                </div>

                <hr class="opacity-10 my-4">

                <form action="{{ route('activation.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="font-2xs text-muted fw-bold text-uppercase ls-wide mb-2 d-block">Clé d'Activation</label>
                        <input type="text" name="license_key" class="form-control form-control-lg border-0 bg-light rounded-4 px-4 fw-bold font-monospace" 
                               placeholder="XXXX-XXXX-XXXX-XXXX" required autocomplete="off">
                    </div>

                    <div class="d-grid shadow-sm">
                        <button type="submit" class="btn btn-navy btn-lg rounded-pill fw-bold py-3 text-uppercase ls-wide small">
                            Vérifier la Licence
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="bg-light p-4 border-top text-center">
                <p class="font-2xs text-muted mb-0">Assistance Technique & Déploiement</p>
                <a href="mailto:support@depotgaz.com" class="font-2xs fw-bold text-navy text-decoration-none">SUPPORT@DEPOTGAZ.COM</a>
            </div>
        </div>

        <div class="text-center mt-5">
            <p class="font-2xs text-muted opacity-50">&copy; {{ date('Y') }} Eugene-Hed. Tous droits réservés.</p>
        </div>
    </div>

    <script>
        function copyID() {
            const id = document.getElementById('installID').innerText;
            navigator.clipboard.writeText(id).then(() => {
                alert('ID copié dans le presse-papier');
            });
        }
    </script>
</body>
</html>
