@extends('layouts.app')

@section('title', 'Base Clients')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Base de Données Clients</h1>
        <p class="text-secondary small mb-0">Gestion de la fidélité et des coordonnées clients</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('clients.create') }}" class="btn btn-navy rounded-pill px-4 btn-sm fw-bold">
            <i class="bi bi-person-plus me-1"></i> Nouveau Client
        </a>
    </div>
</div>

<!-- Statistiques Clients -->
<div class="row g-3 mb-5">
    @php
        $totalClients = $clients->total();
        $clientsActifs = $clients->filter(fn($c) => $c->statut === 'actif')->count();
        $totalPoints = $clients->sum('points_fidelite');
    @endphp

    <div class="col-md-4">
        <div class="card card-corporate border-start border-primary border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Total Portefeuille</span>
                <h3 class="fw-bold text-navy mb-0">{{ $totalClients }} <small class="text-muted small">Membres</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-corporate border-start border-success border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Taux d'Activité</span>
                <h3 class="fw-bold text-navy mb-0">{{ $clientsActifs }} <small class="text-muted small">Actifs</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-corporate border-start border-amber border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Points Fidélité Cumulés</span>
                <h3 class="fw-bold text-navy mb-0">{{ number_format($totalPoints, 0, ',', ' ') }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des Clients -->
<div class="card card-corporate border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">IDENTITÉ DU CLIENT</th>
                        <th>COORDONNÉES</th>
                        <th class="text-center">POINTS</th>
                        <th class="text-center">STATUT</th>
                        <th>MEMBRE DEPUIS</th>
                        <th class="pe-4 text-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-sm bg-light-navy text-navy me-3 fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                                        {{ substr($client->nom_complet, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold text-navy small">{{ $client->nom_complet }}</p>
                                        <span class="text-muted font-2xs">ID #{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="small">
                                <div class="mb-1"><i class="bi bi-telephone text-secondary me-2"></i>{{ $client->telephone ?: 'Non renseigné' }}</div>
                                @if($client->email)
                                    <div><i class="bi bi-envelope text-secondary me-2"></i>{{ $client->email }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-navy border rounded-pill px-3 font-2xs fw-bold">
                                    <i class="bi bi-star-fill text-warning me-1"></i> {{ $client->points_fidelite }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($client->statut === 'actif')
                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill px-3 font-2xs fw-bold">ACTIF</span>
                                @else
                                    <span class="badge bg-light text-secondary border rounded-pill px-3 font-2xs fw-bold">INACTIF</span>
                                @endif
                            </td>
                            <td class="small text-secondary">
                                {{ $client->created_at->format('d M Y') }}
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-light border" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light border text-danger" title="Supprimer" onclick="confirmDelete('{{ route('clients.destroy', $client) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-2 d-block mb-2"></i>
                                Aucun client dans la base.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($clients->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $clients->links() }}
    </div>
@endif

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete(url) {
        if (confirm('Supprimer définitivement ce client ? Cette action est irréversible.')) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection
