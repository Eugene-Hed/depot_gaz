<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | {{ config('app.name') }}</title>
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
            max-width: 440px;
            margin: auto;
            padding: 20px;
        }
        .card-login {
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
        .form-control-corp {
            background-color: #f1f5f9;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 12px 16px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .form-control-corp:focus {
            background-color: #ffffff;
            border-color: var(--navy);
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05);
            outline: none;
        }
        .brand-icon {
            width: 56px;
            height: 56px;
            background-color: var(--navy);
            color: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 1.5rem;
            box-shadow: 0 8px 16px -4px rgba(15, 23, 42, 0.2);
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="text-center mb-5">
            <h1 class="h4 fw-extrabold text-navy text-uppercase ls-wide mb-1">Dépôt Gaz <span class="text-secondary fw-normal">| Platform</span></h1>
            <p class="text-muted small">Solution de gestion intelligente de stocks</p>
        </div>

        <div class="card-login">
            <div class="p-4 p-md-5">
                <div class="brand-icon">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                
                <h4 class="fw-bold text-center text-navy mb-4">Accès Backend</h4>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 small">
                        @foreach ($errors->all() as $error)
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="font-2xs text-muted fw-bold text-uppercase ls-wide mb-2 d-block">Identifiant Agent</label>
                        <input type="text" class="form-control form-control-corp @error('username') is-invalid @enderror"
                            id="username" name="username" value="{{ old('username') }}" required autofocus placeholder="Nom d'utilisateur">
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="password" class="font-2xs text-muted fw-bold text-uppercase ls-wide mb-0 d-block">Mot de Passe</label>
                        </div>
                        <input type="password" class="form-control form-control-corp @error('password') is-invalid @enderror"
                            id="password" name="password" required placeholder="••••••••">
                    </div>

                    <div class="mb-4 d-flex align-items-center">
                        <input class="form-check-input mt-0" type="checkbox" name="remember" id="remember" checked>
                        <label class="form-check-label ms-2 small text-secondary" for="remember">
                            Maintenir la session active
                        </label>
                    </div>

                    <div class="d-grid shadow-sm">
                        <button type="submit" class="btn btn-navy btn-lg rounded-pill fw-bold py-3 text-uppercase ls-wide small">
                            Authentification
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="bg-light p-4 border-top text-center">
                <p class="font-2xs text-muted mb-0">Sécurité Opérationnelle &middot; Traçabilité Logistique</p>
            </div>
        </div>

        <div class="text-center mt-5">
            <p class="font-2xs text-muted opacity-50">&copy; {{ date('Y') }} Eugene-Hed. Système de distribution de gaz.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
