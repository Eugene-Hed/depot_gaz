@extends('layouts.app')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Gestion de la Trésorerie</h1>
        <p class="text-secondary small mb-0">Suivi des encaissements et règlements clients</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('paiements.create') }}" class="btn btn-navy rounded-pill px-4 btn-sm fw-bold">
            <i class="bi bi-plus-lg me-1"></i> Nouveau Paiement
        </a>
    </div>
</div>

<!-- Tableau des Paiements -->
<div class="card card-corporate border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">RÉFÉRENCE</th>
                        <th>CLIENT</th>
                        <th>TRANSACTION</th>
                        <th class="text-end">MONTANT PAYÉ</th>
                        <th class="text-center">MODE</th>
                        <th>NOTES / RÉFÉRENCE</th>
                        <th class="text-center">STATUT</th>
                        <th>DATE</th>
                        <th class="pe-4 text-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paiements as $paiement)
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="fw-bold text-navy small">#{{ str_pad($paiement->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="small fw-bold text-navy">
                                {{ $paiement->client->nom_complet ?? 'N/A' }}
                            </td>
                            <td class="small text-secondary">
                                #{{ $paiement->transaction->numero_transaction ?? 'N/A' }}
                            </td>
                            <td class="text-end">
                                <span class="fw-bold text-navy small">{{ number_format($paiement->montant_paye, 0, ',', ' ') }} F</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-subtle rounded-pill font-2xs px-2">{{ $paiement->mode_paiement }}</span>
                            </td>
                            <td>
                                <span class="small text-secondary italic text-truncate d-inline-block" style="max-width: 150px;" title="{{ $paiement->notes }}">
                                    {{ $paiement->notes ?: '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($paiement->statut === 'recu')
                                    <span class="badge bg-warning-subtle text-warning border border-warning border-opacity-25 rounded-pill px-2 font-2xs fw-bold">REÇU</span>
                                @elseif($paiement->statut === 'confirme')
                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill px-2 font-2xs fw-bold">CONFIRMÉ</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 rounded-pill px-2 font-2xs fw-bold">REFUSÉ</span>
                                @endif
                            </td>
                            <td class="small text-secondary">
                                {{ $paiement->date_paiement->format('d/m/Y') }}
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('paiements.show', $paiement) }}" class="btn btn-sm btn-light border" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('paiements.edit', $paiement) }}" class="btn btn-sm btn-light border" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light border text-danger" title="Supprimer" onclick="confirmDelete('{{ route('paiements.destroy', $paiement) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-cash-coin fs-2 d-block mb-2"></i>
                                Aucun paiement enregistré.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($paiements->hasPages())
    <div class="d-flex justify-content-center mt-4 pb-4">
        {{ $paiements->links() }}
    </div>
@endif

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete(url) {
        if (confirm('Supprimer définitivement ce paiement ?')) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection
