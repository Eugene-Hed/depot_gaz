@extends('layouts.app')

@section('title', 'Gestion des Stocks')

@section('content')
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-boxes"></i> Gestion des Stocks</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('stocks.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouveau mouvement
            </a>
        </div>
    </div>

    <!-- Statistiques KPI -->
    <div class="row mb-4">
        @php
            $totalStocks = $stocks->sum(function($s) { return $s->quantite_pleine + $s->quantite_vide; });
            $totalPleines = $stocks->sum('quantite_pleine');
            $totalVides = $stocks->sum('quantite_vide');
            $stocksEnRupture = $stocks->filter(function($s) { 
                return $s->quantite_pleine < $s->typeBouteille->seuil_alerte; 
            })->count();
        @endphp
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total bouteilles</p>
                            <h4 class="mb-0">{{ $totalStocks }}</h4>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="bi bi-boxes"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Bouteilles pleines</p>
                            <h4 class="mb-0 text-success">{{ $totalPleines }}</h4>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Bouteilles vides</p>
                            <h4 class="mb-0 text-warning">{{ $totalVides }}</h4>
                        </div>
                        <div class="text-warning" style="font-size: 2rem;">
                            <i class="bi bi-exclamation-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Ruptures</p>
                            <h4 class="mb-0 text-danger">{{ $stocksEnRupture }}</h4>
                        </div>
                        <div class="text-danger" style="font-size: 2rem;">
                            <i class="bi bi-exclamation-octagon-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes ruptures -->
    @if($stocksEnRupture > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Attention!</strong> {{ $stocksEnRupture }} produit(s) en rupture de stock. Vérifiez ci-dessous.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tableau des stocks -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Inventaire complet</h5>
                </div>
                <div class="col-auto">
                    <small class="text-muted">{{ $stocks->total() }} article(s)</small>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produit</th>
                        <th>Marque</th>
                        <th class="text-center">Pleines</th>
                        <th class="text-center">Vides</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Seuil</th>
                        <th class="text-center">% Dispo</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        @php
                            $total = $stock->quantite_pleine + $stock->quantite_vide;
                            $pourcentage = $stock->quantite_pleine / max($stock->typeBouteille->seuil_alerte, 1) * 100;
                            $estEnRupture = $stock->quantite_pleine < $stock->typeBouteille->seuil_alerte;
                        @endphp
                        <tr class="{{ $estEnRupture ? 'table-danger' : '' }}">
                            <td>
                                <strong>{{ $stock->typeBouteille->nom }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $stock->typeBouteille->marque->nom }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $stock->quantite_pleine }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning">{{ $stock->quantite_vide }}</span>
                            </td>
                            <td class="text-center">
                                <strong>{{ $total }}</strong>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">{{ $stock->typeBouteille->seuil_alerte }}</small>
                            </td>
                            <td class="text-center">
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $estEnRupture ? 'bg-danger' : 'bg-success' }}" 
                                         role="progressbar" 
                                         style="width: {{ min($pourcentage, 100) }}%"
                                         aria-valuenow="{{ min($pourcentage, 100) }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <small>{{ round(min($pourcentage, 100)) }}%</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($estEnRupture)
                                    <span class="badge bg-danger">
                                        <i class="bi bi-exclamation-octagon"></i> Rupture
                                    </span>
                                @elseif($pourcentage < 75)
                                    <span class="badge bg-warning">
                                        <i class="bi bi-exclamation-triangle"></i> Faible
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> OK
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('stocks.edit', $stock) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Ajuster">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('stocks.show', $stock) }}" 
                                       class="btn btn-outline-info" 
                                       title="Historique">
                                        <i class="bi bi-clock-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Aucun stock
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $stocks->links() }}
    </div>

    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        .progress {
            background-color: #f5f5f5;
        }
        .table-danger {
            background-color: #ffe6e6;
        }
    </style>
@endsection
