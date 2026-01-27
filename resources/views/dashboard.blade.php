@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Alerte Période de Test -->
    @php
        $trial = \App\Helpers\TrialManager::getTrialStatus();
    @endphp

    @if($trial['active'])
        @if($trial['remaining_days'] <= 7)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i> <strong>Attention!</strong> 
                        Votre période de test expire dans <strong>{{ $trial['remaining_days'] }} jour(s)</strong> 
                        (le {{ $trial['end_date']->format('d/m/Y') }})
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        @elseif($trial['remaining_days'] <= 14)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle"></i> <strong>Information:</strong> 
                        Votre période de test expire dans <strong>{{ $trial['remaining_days'] }} jour(s)</strong> 
                        (le {{ $trial['end_date']->format('d/m/Y') }})
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0"><i class="bi bi-speedometer2"></i> Tableau de Bord</h1>
            <p class="text-muted small">{{ date('d/m/Y') }} - Vue d'ensemble de votre activité</p>
        </div>
    </div>

    <!-- Alertes -->
    @if(count($alerts) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm border-start border-4 border-warning">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-exclamation-circle"></i> Alertes</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($alerts as $alert)
                                <div class="col-lg-6 mb-2">
                                    <div class="alert alert-{{ $alert['type'] }} mb-0" role="alert">
                                        <i class="bi bi-{{ $alert['icon'] }}"></i>
                                        <small class="ms-2">{{ $alert['message'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle"></i> <strong>Tout va bien!</strong> Aucune alerte pour le moment.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- KPI Principales -->
    <div class="row mb-4">
        <!-- Revenu du Mois -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Revenu du Mois</p>
                            <h4 class="mb-0">{{ number_format($statsTransactions['total_month'], 0, ',', ' ') }} <small class="text-muted">FCFA</small></h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> Montant total
                            </small>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-success-subtle text-success rounded-circle p-3">
                                <i class="bi bi-currency-dollar" style="font-size: 1.2rem;"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Aujourd'hui -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Transactions Aujourd'hui</p>
                            <h4 class="mb-0">{{ $statsTransactions['today'] }}</h4>
                            <small class="text-info">
                                <i class="bi bi-calendar-event"></i> {{ $statsTransactions['month'] }} ce mois
                            </small>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-info-subtle text-info rounded-circle p-3">
                                <i class="bi bi-graph-up" style="font-size: 1.2rem;"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Total -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Stock Total</p>
                            <h4 class="mb-0">{{ number_format($statsStock['total'], 0) }}</h4>
                            <small class="text-warning">
                                <i class="bi bi-exclamation-circle"></i> {{ $statsStock['rupture'] }} en alerte
                            </small>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-warning-subtle text-warning rounded-circle p-3">
                                <i class="bi bi-box-seam" style="font-size: 1.2rem;"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients Actifs -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Clients Actifs</p>
                            <h4 class="mb-0">{{ $statsClients['actifs'] }} / {{ $statsClients['total'] }}</h4>
                            <small class="text-primary">
                                <i class="bi bi-person-check"></i> Clients
                            </small>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-primary-subtle text-primary rounded-circle p-3">
                                <i class="bi bi-people" style="font-size: 1.2rem;"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique Ventes -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Ventes - Derniers 7 jours</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Stock par Type -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-diagram-3"></i> État du Stock</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($stockByType as $stock)
                            @php
                                $total = $stock['pleine'] + $stock['vide'];
                                $pourcentage_pleine = $total > 0 ? round(($stock['pleine'] / $total) * 100) : 0;
                                $pourcentage_vide = $total > 0 ? 100 - $pourcentage_pleine : 0;
                            @endphp
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <p class="mb-1 fw-bold">{{ $stock['name'] }}</p>
                                        <div class="d-flex gap-2">
                                            <small class="badge bg-primary">
                                                <i class="bi bi-droplet-fill"></i> {{ $stock['pleine'] }} pleines
                                            </small>
                                            <small class="badge bg-secondary">
                                                <i class="bi bi-droplet"></i> {{ $stock['vide'] }} vides
                                            </small>
                                        </div>
                                    </div>
                                    <small class="badge bg-dark rounded-pill">{{ $total }} total</small>
                                </div>
                                <div class="progress" style="height: 24px;">
                                    @if($stock['pleine'] > 0)
                                        <div class="progress-bar bg-success" style="width: {{ $pourcentage_pleine }}%;" title="Pleines">
                                            <small class="fw-bold text-white">{{ $pourcentage_pleine }}%</small>
                                        </div>
                                    @endif
                                    @if($stock['vide'] > 0)
                                        <div class="progress-bar bg-warning" style="width: {{ $pourcentage_vide }}%;" title="Vides">
                                            <small class="fw-bold text-dark">{{ $pourcentage_vide }}%</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">Aucune donnée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Produits et Transactions -->
    <div class="row mb-4">
        <!-- Top 5 Produits -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-star"></i> Top 5 Produits</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($topProducts as $idx => $product)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <div>
                                    <span class="badge bg-dark me-2">{{ $idx + 1 }}</span>
                                    <div>
                                        <strong>{{ $product->marque->nom ?? 'N/A' }} {{ $product->taille }}L</strong>
                                        <br>
                                        <small class="text-muted">{{ number_format($product->prix_vente, 0, ',', ' ') }} FCFA</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="badge bg-success">{{ $product->transactions_count }}</div>
                                    <small class="d-block text-muted mt-1">ventes</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">Aucune donnée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Récentes -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Transactions Récentes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $trans)
                                    <tr>
                                        <td><small>{{ $trans->created_at->format('d/m H:i') }}</small></td>
                                        <td>
                                            <span class="badge bg-{{ in_array($trans->type, ['achat_simple', 'achat_gros']) ? 'success' : 'info' }}">
                                                {{ ucfirst(str_replace('_', ' ', $trans->type)) }}
                                            </span>
                                        </td>
                                        <td class="text-end"><small>{{ number_format($trans->montant_net, 0, ',', ' ') }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3"><small>Aucune transaction</small></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($recentTransactions->count() > 0)
                    <div class="card-footer bg-light">
                        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">Voir toutes</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart')?.getContext('2d');
        if (salesCtx) {
            const salesData = {!! json_encode($salesByDay->map(fn($s) => ['date' => date('d/m', strtotime($s->date)), 'count' => $s->count, 'total' => $s->total])->values()) !!};
            
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: salesData.map(d => d.date),
                    datasets: [{
                        label: 'Nombre de transactions',
                        data: salesData.map(d => d.count),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#0d6efd',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    });
</script>

<style>
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1) !important; }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1) !important; }
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1) !important; }
    
    .text-success { color: #198754; }
    .text-info { color: #0dcaf0; }
    .text-warning { color: #ffc107; }
    .text-primary { color: #0d6efd; }
    
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection

