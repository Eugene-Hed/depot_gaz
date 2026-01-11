@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0"><i class="bi bi-speedometer2"></i> Tableau de Bord</h1>
            <p class="text-muted small">{{ date('d/m/Y') }} - Vue d'ensemble de votre activité</p>
        </div>
    </div>

    <!-- Cartes Principales - Row 1 -->
    <div class="row mb-4">
        <!-- Revenue Month -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Revenu du Mois</p>
                            <h4 class="mb-0">{{ number_format($statsTransactions['total_month'], 0, ',', ' ') }} <small class="text-muted">FCFA</small></h4>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> +12% vs dernier mois
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

        <!-- Transactions Today -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Transactions Aujourd'hui</p>
                            <h4 class="mb-0">{{ $statsTransactions['today'] }}</h4>
                            <small class="text-info">
                                <i class="bi bi-arrow-up"></i> {{ $statsTransactions['month'] }} ce mois
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
                                <i class="bi bi-exclamation-circle"></i> {{ $statsStock['rupture'] }} en rupture
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
                                <i class="bi bi-person-check"></i> {{ $statsClients['avec_dette'] }} avec dette
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

    <!-- Cartes Secondaires - Row 2 -->
    <div class="row mb-4">
        <!-- Paiements Today -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Paiements Aujourd'hui</p>
                            <h4 class="mb-0">{{ number_format($statsPaiements['today'], 0, ',', ' ') }} <small class="text-muted">FCFA</small></h4>
                            <small class="text-success">
                                <i class="bi bi-check-circle"></i> {{ $statsPaiements['pending'] }} en attente
                            </small>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-success-subtle text-success rounded-circle p-3">
                                <i class="bi bi-credit-card" style="font-size: 1.2rem;"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dettes Clients -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Dettes Clients</p>
                            <h4 class="mb-0">{{ number_format($statsClients['total_dette'], 0, ',', ' ') }} <small class="text-muted">FCFA</small></h4>
                            <small class="text-danger">
                                <i class="bi bi-exclamation-triangle"></i> À recouvrir
                            </small>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-danger-subtle text-danger rounded-circle p-3">
                                <i class="bi bi-exclamation-circle" style="font-size: 1.2rem;"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes Pendantes -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Commandes Pendantes</p>
                            <h4 class="mb-0">{{ $statsCommandes['pending'] }}</h4>
                            <small class="text-info">
                                <i class="bi bi-truck"></i> {{ $statsCommandes['total_month'] }} ce mois
                            </small>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-info-subtle text-info rounded-circle p-3">
                                <i class="bi bi-bag" style="font-size: 1.2rem;"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Year -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Revenu Annuel</p>
                            <h4 class="mb-0">{{ number_format($statsTransactions['total_year'], 0, ',', ' ') }} <small class="text-muted">FCFA</small></h4>
                            <small class="text-success">
                                <i class="bi bi-calendar-event"></i> 2025
                            </small>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-success-subtle text-success rounded-circle p-3">
                                <i class="bi bi-calendar3-event" style="font-size: 1.2rem;"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <!-- Ventes derniers 7 jours -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Ventes derniers 7 jours</h5>
                    <span class="badge bg-info">{{ $salesByDay->count() }} jours</span>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Modes de paiement -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Modes de paiement</h5>
                    <span class="badge bg-success">{{ $paymentModes->count() }} modes</span>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="paymentChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Produits et Stock -->
    <div class="row mb-4">
        <!-- Top 5 Produits -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-star"></i> Top 5 Produits les plus vendus</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($topProducts as $idx => $product)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-dark me-2">{{ $idx + 1 }}</span>
                                    <strong>{{ $product->nom }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->transactions_count }} ventes</small>
                                </div>
                                <div class="text-end">
                                    <div class="progress" style="width: 100px; height: 5px;">
                                        <div class="progress-bar bg-success" style="width: {{ min(($product->transactions_count / $topProducts->first()->transactions_count) * 100, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">Aucune donnée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock par type -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Stock par type de bouteille</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($stockByType as $stock)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-bold">{{ $stock['name'] }}</small>
                                    <small class="text-muted">{{ $stock['pleine'] + $stock['vide'] }} total</small>
                                </div>
                                <div class="row g-1">
                                    <div class="col-6">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-primary" title="Pleines" style="width: {{ $stock['pleine'] > 0 ? min(($stock['pleine'] / max($stock['pleine'], $stock['vide'], 1)) * 100, 100) : 0 }}%">
                                                <small>{{ $stock['pleine'] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-secondary" title="Vides" style="width: {{ $stock['vide'] > 0 ? min(($stock['vide'] / max($stock['pleine'], $stock['vide'], 1)) * 100, 100) : 0 }}%">
                                                <small>{{ $stock['vide'] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">Aucune donnée</p>
                        @endforelse
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <span class="badge bg-primary">Pleines</span>
                        <span class="badge bg-secondary">Vides</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions et Paiements Récents -->
    <div class="row mb-4">
        <!-- Transactions Récentes -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Transactions Récentes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Bouteille</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $trans)
                                    <tr>
                                        <td><small>{{ $trans->created_at->format('d/m H:i') }}</small></td>
                                        <td>
                                            <span class="badge bg-{{ $trans->type === 'vente' ? 'success' : 'info' }}">
                                                {{ ucfirst($trans->type) }}
                                            </span>
                                        </td>
                                        <td><small>{{ $trans->typeBouteille->nom }}</small></td>
                                        <td class="text-end fw-bold"><small>{{ number_format($trans->montant_total, 0, ',', ' ') }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3"><small>Aucune transaction</small></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($recentTransactions->count() > 0)
                    <div class="card-footer bg-light">
                        <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">Voir toutes les transactions</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Paiements Récents -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Paiements Récents</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Mode</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPaiements as $paiement)
                                    <tr>
                                        <td><small>{{ $paiement->date_paiement->format('d/m H:i') }}</small></td>
                                        <td><small>{{ $paiement->client->nom ?? 'N/A' }}</small></td>
                                        <td>
                                            <small class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $paiement->mode_paiement)) }}</small>
                                        </td>
                                        <td class="text-end fw-bold"><small>{{ number_format($paiement->montant_paye, 0, ',', ' ') }}</small></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3"><small>Aucun paiement</small></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($recentPaiements->count() > 0)
                    <div class="card-footer bg-light">
                        <a href="{{ route('paiements.index') }}" class="btn btn-sm btn-outline-primary">Voir tous les paiements</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Alertes -->
    @if(count($alerts) > 0)
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-exclamation-circle"></i> Alertes & Notifications</h5>
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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <strong>Tout va bien!</strong> Aucune alerte pour le moment.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
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
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Total FCFA',
                        data: salesData.map(d => d.total),
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true },
                        y1: { position: 'right', beginAtZero: true }
                    }
                }
            });
        }

        // Payment Modes Chart
        const paymentCtx = document.getElementById('paymentChart')?.getContext('2d');
        if (paymentCtx) {
            const paymentData = {!! json_encode($paymentModes->map(fn($p) => ['mode' => ucfirst(str_replace('_', ' ', $p->mode_paiement)), 'count' => $p->count, 'total' => $p->total])) !!};
            
            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentData.map(p => p.mode),
                    datasets: [{
                        data: paymentData.map(p => p.total),
                        backgroundColor: [
                            '#0d6efd',
                            '#6f42c1',
                            '#20c997',
                            '#fd7e14',
                            '#dc3545'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    });
</script>

<style>
    .badge-subtle {
        background-color: rgba(0,0,0,.03);
    }
    
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1) !important; }
    .bg-info-subtle { background-color: rgba(13, 202, 240, 0.1) !important; }
    .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.1) !important; }
    .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.1) !important; }
    .bg-danger-subtle { background-color: rgba(220, 53, 69, 0.1) !important; }
    
    .text-success { color: #198754; }
    .text-info { color: #0dcaf0; }
    .text-warning { color: #ffc107; }
    .text-primary { color: #0d6efd; }
    .text-danger { color: #dc3545; }
    
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection

