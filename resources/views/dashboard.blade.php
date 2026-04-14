@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container-fluid py-4 dashboard-corporate">
    <!-- Alerte Période de Test / Licence (Discrète) -->
    @php
        $trial = \App\Helpers\TrialManager::getTrialStatus();
    @endphp

    @if($trial['active'] && $trial['remaining_days'] <= 7)
        <div class="alert trial-bar border-0 shadow-sm mb-4 d-flex align-items-center">
            <i class="bi bi-shield-lock me-3 text-warning fs-5"></i>
            <div class="flex-grow-1">
                <span class="fw-medium text-dark-emphasis">Période d'évaluation - {{ $trial['remaining_days'] }} jours restants.</span>
                <a href="{{ route('activation.index') }}" class="ms-2 text-primary fw-bold text-decoration-none small">ACTIVER LA LICENCE <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    @endif

    <!-- Header & Welcome -->
    <div class="row mb-4 align-items-end">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Tableau de Bord</h1>
            <p class="text-secondary small mb-0">Rapport d'activité consolidé au {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="time-display small text-secondary">
                <i class="bi bi-clock me-1"></i> Dernière mise à jour: {{ now()->format('H:i') }}
            </div>
        </div>
    </div>

    <!-- Alertes Stocks (Concises) -->
    @if(count($alerts) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-secondary border-0 bg-white shadow-sm p-3 d-flex align-items-center overflow-hidden">
                    <span class="badge bg-navy me-3">ALERTE</span>
                    <marquee behavior="scroll" direction="left" class="small text-secondary fw-medium">
                        @foreach($alerts as $alert)
                            • {{ $alert['message'] }} &nbsp;&nbsp;&nbsp;&nbsp;
                        @endforeach
                    </marquee>
                </div>
            </div>
        </div>
    @endif

    <!-- KPI Principales - Style Sobre & Corporate -->
    <div class="row g-3 mb-4">
        <!-- Revenu Mensuel -->
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card-sober border-start-navy shadow-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="label text-muted text-uppercase fw-bold ls-1 small">Revenu du Mois</span>
                        <div class="icon bg-light-navy text-navy"><i class="bi bi-wallet2"></i></div>
                    </div>
                    <div class="d-flex align-items-baseline">
                        <h3 class="value fw-bold text-navy mb-0">{{ number_format($statsTransactions['total_month'], 0, ',', ' ') }}</h3>
                        <span class="currency ms-1 text-muted small">FCFA</span>
                    </div>
                    <div class="mt-2 text-success small fw-medium">
                        <i class="bi bi-graph-up-arrow me-1"></i> Performance stable
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Jour -->
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card-sober border-start-blue shadow-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="label text-muted text-uppercase fw-bold ls-1 small">Ventes / Jour</span>
                        <div class="icon bg-light-blue text-primary"><i class="bi bi-cart3"></i></div>
                    </div>
                    <h3 class="value fw-bold text-navy mb-0">{{ $statsTransactions['today'] }}</h3>
                    <div class="mt-2 text-muted small">
                        Contre {{ $statsTransactions['month'] }} ce mois
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Total -->
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card-sober border-start-amber shadow-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="label text-muted text-uppercase fw-bold ls-1 small">Stock Global</span>
                        <div class="icon bg-light-amber text-warning"><i class="bi bi-box-seam"></i></div>
                    </div>
                    <h3 class="value fw-bold text-navy mb-0">{{ number_format($statsStock['total'], 0) }}</h3>
                    <div class="mt-2 text-{{ $statsStock['rupture'] > 0 ? 'danger' : 'success' }} small fw-medium">
                        <i class="bi bi-{{ $statsStock['rupture'] > 0 ? 'exclamation-triangle' : 'check-circle' }} me-1"></i> {{ $statsStock['rupture'] }} alertes stock
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients Portefolio -->
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card-sober border-start-slate shadow-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="label text-muted text-uppercase fw-bold ls-1 small">Total Clients</span>
                        <div class="icon bg-light-slate text-secondary"><i class="bi bi-people"></i></div>
                    </div>
                    <h3 class="value fw-bold text-navy mb-0">{{ $statsClients['total'] }}</h3>
                    <div class="mt-2 text-muted small">
                        Dont {{ $statsClients['actifs'] }} actifs
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Stock Visualization -->
    <div class="row g-4 mb-4">
        <!-- Ventes Area Chart (Sobres) -->
        <div class="col-xl-8">
            <div class="card border-0 bg-white shadow-sm rounded-3">
                <div class="card-header bg-transparent border-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-navy mb-0">ÉVOLUTION DES VENTES (7J)</h6>
                    <div class="badge bg-light text-secondary rounded-pill fw-normal px-3">Lissage Hebdo</div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 300px;">
                        <canvas id="corporateSalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition Stock -->
        <div class="col-xl-4">
            <div class="card border-0 bg-white shadow-sm rounded-3 h-100">
                <div class="card-header bg-transparent border-0 px-4 pt-4">
                    <h6 class="fw-bold text-navy mb-0">NIVEAU DE STOCK PAR TYPE</h6>
                </div>
                <div class="card-body px-4 pb-4 overflow-auto" style="max-height: 300px;">
                    <div class="list-group list-group-flush">
                        @foreach($stockByType as $stock)
                            @php
                                $total = $stock['pleine'] + $stock['vide'];
                                $fullPerc = $total > 0 ? ($stock['pleine'] / $total) * 100 : 0;
                            @endphp
                            <div class="list-group-item border-0 px-0 mb-2">
                                <div class="d-flex justify-content-between mb-1 align-items-center small">
                                    <span class="fw-bold text-navy">{{ $stock['name'] }}</span>
                                    <span class="text-secondary">{{ $total }}</span>
                                </div>
                                <div class="progress rounded-0" style="height: 4px; background-color: #f1f5f9;">
                                    <div class="progress-bar bg-navy" style="width: {{ $fullPerc }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1 font-2xs text-muted">
                                    <span>Pleine: {{ $stock['pleine'] }}</span>
                                    <span>Vidanges: {{ $stock['vide'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section (Sober & Condensed) -->
    <div class="row g-4">
        <!-- Dernières Transactions -->
        <div class="col-lg-12">
            <div class="card border-0 bg-white shadow-sm rounded-3">
                <div class="card-header bg-transparent border-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-navy mb-0">REGISTRE DES DERNIÈRES OPÉRATIONS</h6>
                        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-navy rounded-pill px-3 fw-bold">Journal Complet</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-modern align-middle mb-0">
                            <thead>
                                <tr class="bg-light text-muted small ls-1">
                                    <th class="ps-4">ID TRANS.</th>
                                    <th>CLIENT</th>
                                    <th>OPÉRATION</th>
                                    <th>BOUTEILLE</th>
                                    <th>DESCRIPTION</th>
                                    <th class="text-end">MONTANT NET</th>
                                    <th class="pe-4 text-end">DATE / HEURE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $trans)
                                    <tr>
                                        <td class="ps-4 py-3 small fw-medium text-secondary">#{{ str_pad($trans->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="small fw-bold text-navy">{{ $trans->client->nom_complet ?? 'Client Inconnu' }}</td>
                                        <td>
                                            <span class="badge border text-secondary font-2xs fw-normal px-2 bg-light">
                                                {{ ucfirst(str_replace('_', ' ', $trans->type)) }}
                                            </span>
                                        </td>
                                        <td class="small text-secondary">{{ $trans->typeBouteille->nom ?? 'Inconnu' }}</td>
                                        <td>
                                            <span class="font-2xs text-secondary italic text-truncate d-inline-block" style="max-width: 120px;" title="{{ $trans->commentaire }}">
                                                {{ $trans->commentaire ?: '-' }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold text-navy small">{{ number_format($trans->montant_net, 0, ',', ' ') }} FCFA</td>
                                        <td class="pe-4 text-end text-muted small">
                                            {{ $trans->created_at->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Palette Corporate - Slate & Navy */
    :root {
        --navy: #0f172a;
        --light-navy: rgba(15, 23, 42, 0.05);
        --slate: #475569;
        --light-slate: rgba(71, 85, 105, 0.05);
        --accent-blue: #3b82f6;
        --light-blue: rgba(59, 130, 246, 0.08);
        --amber: #d97706;
        --light-amber: rgba(217, 119, 6, 0.08);
    }

    .ls-wide { letter-spacing: 2px; }
    .ls-1 { letter-spacing: 0.5px; }
    .text-navy { color: var(--navy); }
    .bg-navy { background-color: var(--navy) !important; color: white !important; }
    .bg-light-navy { background-color: var(--light-navy); }
    .bg-light-blue { background-color: var(--light-blue); }
    .bg-light-amber { background-color: var(--light-amber); }
    .bg-light-slate { background-color: var(--light-slate); }
    .border-start-navy { border-left: 4px solid var(--navy) !important; }
    .border-start-blue { border-left: 4px solid var(--accent-blue) !important; }
    .border-start-amber { border-left: 4px solid var(--amber) !important; }
    .border-start-slate { border-left: 4px solid var(--slate) !important; }

    .dashboard-corporate {
        background-color: #f8fafc;
        min-height: 100vh;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    /* KPI Cards - Corporate style */
    .kpi-card-sober {
        background: white;
        border-radius: 8px;
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .shadow-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .kpi-card-sober .icon {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .kpi-card-sober .value {
        font-size: 1.5rem;
        letter-spacing: -0.5px;
    }

    /* Trial Bar */
    .trial-bar {
        background: white;
        border-left: 4px solid #f59e0b !important;
        border-radius: 8px;
    }

    /* Condensed Table */
    .table-modern thead th {
        padding: 12px 15px;
        border-bottom: 2px solid #f1f5f9;
        font-weight: 700;
    }

    .table-modern tbody td {
        border-bottom: 1px solid #f1f5f9;
    }

    .font-2xs { font-size: 0.65rem; }

    .btn-outline-navy {
        border: 1px solid var(--navy);
        color: var(--navy);
        transition: all 0.2s;
    }
    .btn-outline-navy:hover {
        background-color: var(--navy);
        color: white;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('corporateSalesChart')?.getContext('2d');
        if (ctx) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(15, 23, 42, 0.1)');
            gradient.addColorStop(1, 'rgba(15, 23, 42, 0)');

            const salesData = {!! json_encode($salesByDay->map(fn($s) => ['date' => date('d/m', strtotime($s->date)), 'total' => $s->total])->values()) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.map(d => d.date),
                    datasets: [{
                        label: 'Ventes',
                        data: salesData.map(d => d.total),
                        borderColor: '#0f172a',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        tension: 0.2, // Plus sobre, moins de courbes accentuées
                        fill: true,
                        pointBackgroundColor: '#0f172a',
                        pointRadius: 0,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9', drawBorder: false },
                            ticks: { font: { size: 10 }, color: '#94a3b8' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 }, color: '#94a3b8' }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
