<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            position: relative;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .login-box {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            padding: 50px 40px;
            z-index: 1;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .login-box h1 {
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            font-size: 32px;
            font-weight: 700;
        }
        
        .login-subtitle {
            text-align: center;
            color: #999;
            font-size: 14px;
            margin-bottom: 30px;
            font-weight: 500;
        }
        
        .form-control {
            border: 2px solid #f0f0f0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background-color: white;
        }
        
        .form-label {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-top: 10px;
            color: white;
        }
        
        .btn-login:hover {
            color: white;
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .form-check-label {
            color: #666;
            font-size: 14px;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>{{ config('app.name') }}</h1>
            <p class="login-subtitle">Gestion de Stock Dépôt GAZ</p>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erreur !</strong>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">Identifiant</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                        id="username" name="username" value="{{ old('username') }}" required autofocus>
                    @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                        id="password" name="password" required>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Se souvenir de moi
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-login">Se connecter</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
