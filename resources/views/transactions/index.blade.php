@extends('layouts.app')

@section('title', 'Journal des Transactions')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Journal des Flux</h1>
        <p class="text-secondary small mb-0">Historique détaillé des ventes et échanges</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('transactions.create') }}" class="btn btn-navy rounded-pill px-4 btn-sm fw-bold">
            <i class="bi bi-cart-plus me-1"></i> Nouvelle opération
        </a>
    </div>
</div>

<!-- Statistiques Rapides -->
<div class="row g-3 mb-5">
    @php
        $totalVentes = $transactions->sum('montant_total');
        $ventesAujourd = $transactions->filter(fn($t) => $t->created_at->isToday())->sum('montant_total');
        $nombreVentes = $transactions->count();
    @endphp

    <div class="col-md-4">
        <div class="card card-corporate border-start border-primary border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Volume Global</span>
                <h3 class="fw-bold text-navy mb-0">{{ number_format($totalVentes, 0, ',', ' ') }} <small class="text-muted small">F</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-corporate border-start border-success border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Activité du Jour</span>
                <h3 class="fw-bold text-navy mb-0">{{ number_format($ventesAujourd, 0, ',', ' ') }} <small class="text-muted small">F</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-corporate border-start border-slate border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Total Opérations</span>
                <h3 class="fw-bold text-navy mb-0">{{ $nombreVentes }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des Transactions -->
<div class="card card-corporate border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">DATE / HEURE</th>
                        <th>TYPE</th>
                        <th>PRODUIT</th>
                        <th>CLIENT</th>
                        <th>DESCRIPTION</th>
                        <th class="text-end">MONTANT NET</th>
                        <th class="text-center">STATUT</th>
                        <th class="pe-4 text-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trans)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="fw-bold text-navy small">{{ $trans->created_at->format('d/m/Y') }}</span><br>
                                <span class="text-secondary font-2xs">{{ $trans->created_at->format('H:i') }}</span>
                            </td>
                            <td>
                                @php
                                    $typeLabels = [
                                        'echange_simple' => ['label' => 'Échange Std', 'class' => 'bg-info-subtle text-info border-info'],
                                        'echange_type' => ['label' => 'Échange Type', 'class' => 'bg-warning-subtle text-warning border-warning'],
                                        'achat_simple' => ['label' => 'Achat Simple', 'class' => 'bg-primary-subtle text-primary border-primary'],
                                    ];
                                    $meta = $typeLabels[$trans->type] ?? ['label' => $trans->type, 'class' => 'bg-light text-secondary border-secondary'];
                                @endphp
                                <span class="badge {{ $meta['class'] }} border border-opacity-25 rounded-pill px-2 font-2xs fw-bold">
                                    {{ $meta['label'] }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="fw-bold text-navy">{{ $trans->quantite }} ×</span> {{ $trans->typeBouteille->nom }}
                                    <br><span class="text-muted font-2xs">{{ $trans->typeBouteille->marque->nom }}</span>
                                </div>
                            </td>
                            <td class="small">
                                @if($trans->client)
                                    <span class="fw-bold text-navy">{{ $trans->client->nom_complet }}</span>
                                @else
                                    <span class="text-muted italic small">Passant</span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-secondary italic text-truncate d-inline-block" style="max-width: 150px;" title="{{ $trans->commentaire }}">
                                    {{ $trans->commentaire ?: '-' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold text-navy small">{{ number_format($trans->montant_net, 0, ',', ' ') }} F</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-secondary border rounded-pill font-2xs px-2">{{ $trans->mode_paiement ?: 'N/A' }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('transactions.show', $trans) }}" class="btn btn-sm btn-light border" title="Voir détails">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Aucune transaction enregistrée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mt-4 pb-4">
    {{ $transactions->links() }}
</div>
@endsection
