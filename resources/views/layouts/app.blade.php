<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        body {
            background-color: #f5f5f5;
        }
        .sidebar {
            background-color: #2c3e50;
            min-height: 100vh;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            margin: 10px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #667eea;
            color: white;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .main-content {
            padding: 30px;
        }
        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar">
                <div class="text-center mb-4">
                    <h5 class="text-white">{{ config('app.name') }}</h5>
                    <small class="text-muted">Administrateur</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Tableau de bord
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('stocks.*') ? 'active' : '' }}"
                            href="{{ route('stocks.index') }}">
                            <i class="bi bi-boxes"></i> Stock
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
                            href="{{ route('transactions.index') }}">
                            <i class="bi bi-cart-check"></i> Transactions
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}"
                            href="{{ route('clients.index') }}">
                            <i class="bi bi-people"></i> Clients
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rapports.*') ? 'active' : '' }}"
                            href="{{ route('rapports.index') }}">
                            <i class="bi bi-file-earmark-arrow-down"></i> Rapports
                        </a>
                    </li>

                    @if(Auth::check())
                        <hr class="bg-secondary">
                        <li class="nav-item">
                            <a class="nav-link" href="#settings" data-bs-toggle="collapse">
                                <i class="bi bi-gear"></i> Administration
                            </a>
                            <div class="collapse" id="settings">
                                <ul class="nav flex-column ms-3">
                                    <li><a class="nav-link small {{ request()->routeIs('types-bouteilles.*') ? 'active' : '' }}" href="{{ route('types-bouteilles.index') }}">Types de bouteilles</a></li>
                                    <li><a class="nav-link small {{ request()->routeIs('marques.*') ? 'active' : '' }}" href="{{ route('marques.index') }}">Marques</a></li>
                                    <li><a class="nav-link small {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}" href="{{ route('fournisseurs.index') }}">Fournisseurs</a></li>
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>

                <hr class="bg-secondary mt-4">

                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <!-- Navbar top -->
                <nav class="navbar navbar-expand-lg navbar-custom mb-4">
                    <div class="container-fluid">
                        <span class="navbar-text">
                            Bienvenue <strong>{{ Auth::user()->nom_complet }}</strong>
                        </span>
                        <div class="ms-auto">
                            <span class="text-muted small">
                                <i class="bi bi-calendar"></i> {{ now()->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </nav>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                <div class="main-content">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
