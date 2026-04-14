<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue | Dépôt Gaz Platform</title>
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
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
            color: var(--navy);
            overflow-x: hidden;
        }
        .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            color: white;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 15vh;
            background: linear-gradient(to top, #ffffff, transparent);
        }
        .ls-wide { letter-spacing: 0.05em; }
        .font-2xs { font-size: 0.7rem; }
        .btn-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.3s;
        }
        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
        }
        .btn-white {
            background: white;
            color: var(--navy);
            font-weight: 700;
            transition: all 0.3s;
        }
        .btn-white:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.2);
        }
        .feature-card {
            border: 1px solid rgba(15, 23, 42, 0.05);
            border-radius: 20px;
            padding: 30px;
            transition: all 0.3s;
            background: white;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        }
        .icon-box {
            width: 50px;
            height: 50px;
            background: var(--light-navy);
            color: var(--navy);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-extrabold text-uppercase ls-wide" href="#">
                Dépôt Gaz <span class="fw-light opacity-50">Platform</span>
            </a>
            <div class="ms-auto d-flex gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-glass btn-sm rounded-pill px-4 fw-bold">DASHBOARD</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-glass btn-sm rounded-pill px-4 fw-bold">CONNEXION</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="badge bg-primary bg-opacity-25 text-primary rounded-pill px-3 py-2 fw-bold mb-4 font-2xs ls-wide text-uppercase">Logistique & Distribution v2.0</span>
                    <h1 class="display-3 fw-extrabold mb-4">Gestion Intelligente de votre <span class="text-white opacity-75 italic">Dépôt de Gaz</span></h1>
                    <p class="lead opacity-75 mb-5 pe-lg-5">Optimisez vos stocks, suivez vos transactions en temps réel et fidélisez vos clients avec notre solution backend robuste et sécurisée.</p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('login') }}" class="btn btn-white btn-lg rounded-pill px-5 py-3">Commencer maintenant</a>
                        <a href="#features" class="btn btn-glass btn-lg rounded-pill px-4 py-3"><i class="bi bi-play-circle me-2"></i> Découvrir</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <div class="bg-primary rounded-circle blur-3xl opacity-10 position-absolute" style="width: 300px; height: 300px; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
                        <i class="bi bi-box-seam text-white opacity-10" style="font-size: 15rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="features" class="py-5">
        <div class="container py-5">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box mx-auto">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <h5 class="fw-bold">Rapports Avancés</h5>
                        <p class="text-muted small mb-0">Visualisez vos performances de vente et vos niveaux de stocks en un clin d'œil grâce à notre dashboard intuitif.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box mx-auto">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold">Sécurité & Audit</h5>
                        <p class="text-muted small mb-0">Chaque mouvement est tracé. L'intégrité de vos données est garantie par notre système d'enregistrement immuable.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="icon-box mx-auto">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h5 class="fw-bold">CRM Intégré</h5>
                        <p class="text-muted small mb-0">Gérez vos clients, suivez leur fidélité et optimisez vos relations commerciales pour maximiser votre CA.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-5 border-top bg-light">
        <div class="container text-center">
            <p class="text-muted small mb-0 text-uppercase ls-wide fw-bold opacity-50">&copy; {{ date('Y') }} Eugene-Hed. Solutions Logistiques Modernes.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
