@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-cart-check"></i> Transactions</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouvelle vente
            </a>
        </div>
    </div>

    <!-- Statistiques KPI -->
    <div class="row mb-4">
        @php
            $totalVentes = $transactions->sum('montant_total');
            $totalQuantite = $transactions->sum('quantite');
            $ventesAujourd = $transactions->filter(fn($t) => $t->created_at->isToday())->sum('montant_total');
            $nombreVentes = $transactions->count();
        @endphp
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total ventes</p>
                            <h4 class="mb-0">{{ number_format($totalVentes, 0, ',', ' ') }} F</h4>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="bi bi-graph-up"></i>
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
                            <p class="text-muted mb-0 small">Aujourd'hui</p>
                            <h4 class="mb-0">{{ number_format($ventesAujourd, 0, ',', ' ') }} F</h4>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="bi bi-calendar-today"></i>
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
                            <p class="text-muted mb-0 small">Quantité total</p>
                            <h4 class="mb-0">{{ $totalQuantite }}</h4>
                        </div>
                        <div class="text-warning" style="font-size: 2rem;">
                            <i class="bi bi-box"></i>
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
                            <p class="text-muted mb-0 small">Transactions</p>
                            <h4 class="mb-0">{{ $nombreVentes }}</h4>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des transactions -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Historique des transactions</h5>
                </div>
                <div class="col-auto">
                    <small class="text-muted">{{ $transactions->total() }} transaction(s)</small>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date/Heure</th>
                        <th class="text-center">Type</th>
                        <th>Produit</th>
                        <th class="text-center">Qté</th>
                        <th>Client</th>
                        <th class="text-end">Montant</th>
                        <th>Paiement</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trans)
                        <tr>
                            <td>
                                <small class="text-muted">{{ $trans->created_at->format('d/m/Y') }}</small><br>
                                <strong>{{ $trans->created_at->format('H:i') }}</strong>
                            </td>
                            <td class="text-center">
                                @switch($trans->type)
                                    @case('vente')
                                        <span class="badge bg-success"><i class="bi bi-bag-check"></i> Vente</span>
                                        @break
                                    @case('retour')
                                        <span class="badge bg-danger"><i class="bi bi-arrow-counterclockwise"></i> Retour</span>
                                        @break
                                    @case('recharge')
                                        <span class="badge bg-info"><i class="bi bi-arrow-down"></i> Recharge</span>
                                        @break
                                    @case('echange')
                                        <span class="badge bg-warning"><i class="bi bi-arrow-left-right"></i> Échange</span>
                                        @break
                                    @case('consigne')
                                        <span class="badge bg-secondary"><i class="bi bi-key"></i> Consigne</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ $trans->type }}</span>
                                @endswitch
                            </td>
                            <td>
                                <strong>{{ $trans->typeBouteille->nom }}</strong><br>
                                <small class="text-muted">{{ $trans->typeBouteille->marque->nom }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">{{ $trans->quantite }}</span>
                            </td>
                            <td>
                                @if($trans->client)
                                    <strong>{{ $trans->client->nom }}</strong><br>
                                    <small class="text-muted">{{ $trans->client->telephone ?? '-' }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <strong class="text-success">{{ number_format($trans->montant_total, 0, ',', ' ') }} F</strong><br>
                                <small class="text-muted">@prix: {{ number_format($trans->prix_unitaire, 0, ',', ' ') }} F</small>
                            </td>
                            <td>
                                @if($trans->mode_paiement)
                                    <span class="badge bg-light text-dark">{{ $trans->mode_paiement }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('transactions.show', $trans) }}" class="btn btn-sm btn-outline-info" title="Détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Aucune transaction
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $transactions->links() }}
    </div>

    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endsection
