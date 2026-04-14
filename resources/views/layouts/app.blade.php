<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- UI Foundation -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #0f172a;
            --navy-light: #1e293b;
            --slate: #475569;
            --slate-light: #f8fafc;
            --accent-blue: #3b82f6;
            --border-color: #e2e8f0;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--slate-light);
            color: var(--navy);
            overflow-x: hidden;
        }

        /* Sidebar Corporate Style */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--navy);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            padding: 1.5rem 1rem;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 0.5rem 1rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.5rem;
        }

        .sidebar-brand h5 {
            color: white;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 0;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            font-weight: 500;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }

        .sidebar .nav-link.active {
            color: white;
            background-color: var(--accent-blue);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Main Content Adjustments */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 0;
            transition: all 0.3s ease;
        }

        .top-navbar {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .content-area {
            padding: 2rem;
        }

        /* Global Corporate Components */
        .ls-wide { letter-spacing: 1.5px; }
        .text-navy { color: var(--navy) !important; }
        .font-2xs { font-size: 0.65rem; }

        .btn-navy {
            background-color: var(--navy);
            color: white;
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            transition: all 0.2s;
        }
        .btn-navy:hover {
            background-color: var(--navy-light);
            color: white;
            transform: translateY(-1px);
        }

        .card-corporate {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        /* Standard Table Corporate */
        .table-modern thead th {
            background-color: #f8fafc;
            color: var(--slate);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-bottom: 2px solid var(--border-color);
        }

        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Utilities */
        .badge-subtle {
            background-color: #f1f5f9;
            color: var(--slate);
            font-weight: 600;
            border: 1px solid var(--border-color);
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <h5>DEPOT GAZ</h5>
            <small class="text-white text-opacity-50 font-2xs text-uppercase">Système de Gestion</small>
        </div>

        <ul class="nav flex-column mb-4">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2"></i> Tableau de bord
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('stocks.index') ? 'active' : '' }}" href="{{ route('stocks.index') }}">
                    <i class="bi bi-boxes"></i> Inventaire
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('stocks.mouvements') ? 'active' : '' }}" href="{{ route('stocks.mouvements') }}">
                    <i class="bi bi-arrow-down-up"></i> Audit Stock
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                    <i class="bi bi-cart-check"></i> Transactions
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                    <i class="bi bi-people"></i> Base Clients
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('rapports.*') ? 'active' : '' }}" href="{{ route('rapports.index') }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Analytique
                </a>
            </li>
        </ul>

        @if(Auth::check())
            <p class="text-white text-opacity-25 small fw-bold text-uppercase px-3 mb-2" style="font-size: 0.6rem; letter-spacing: 1px;">Configuration</p>
            <ul class="nav flex-column mb-auto">
                <li><a class="nav-link {{ request()->routeIs('types-bouteilles.*') ? 'active' : '' }}" href="{{ route('types-bouteilles.index') }}"><i class="bi bi-tags"></i> Produits</a></li>
                <li><a class="nav-link {{ request()->routeIs('marques.*') ? 'active' : '' }}" href="{{ route('marques.index') }}"><i class="bi bi-award"></i> Marques</a></li>
                <li><a class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}" href="{{ route('fournisseurs.index') }}"><i class="bi bi-truck"></i> Fournisseurs</a></li>
            </ul>
        @endif

        <div class="mt-5 px-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm w-100 text-white text-opacity-50 hover-danger p-2 text-start">
                    <i class="bi bi-power"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>

    <div class="main-wrapper">
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <span class="text-secondary small fw-medium">
                    <i class="bi bi-geo-alt me-1"></i> Dépôt Central • Activé
                </span>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <div class="text-end d-none d-md-block">
                    <p class="mb-0 fw-bold small text-navy">{{ Auth::user()->nom_complet }}</p>
                    <small class="text-muted font-2xs text-uppercase">Administrateur</small>
                </div>
                <div class="avatar-circle-sm bg-light text-navy fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;">
                    {{ substr(Auth::user()->nom_complet, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="content-area">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm animate__animated animate__fadeInDown" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm animate__animated animate__shakeX" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.add('animate__fadeOutUp');
                    setTimeout(() => alert.remove(), 500);
                }, 4000);
            });
        });
    </script>

    <style>
        .hover-danger:hover {
            color: #ef4444 !important;
            background: rgba(239, 68, 68, 0.1);
        }
    </style>

    @yield('scripts')
</body>
</html>
