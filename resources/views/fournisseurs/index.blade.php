@extends('layouts.app')

@section('title', 'Gestion des Fournisseurs')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Réseau Fournisseurs</h1>
        <p class="text-secondary small mb-0">Gestion de la chaîne d'approvisionnement et contacts logistiques</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('fournisseurs.create') }}" class="btn btn-navy rounded-pill px-4 btn-sm fw-bold">
            <i class="bi bi-person-plus-fill me-1"></i> Nouveau Fournisseur
        </a>
    </div>
</div>

<!-- Statistiques Fournisseurs -->
<div class="row g-3 mb-5">
    @php
        $stats = [
            'total' => $fournisseurs->total(),
            'actifs' => \App\Models\Fournisseur::where('statut', 'actif')->count(),
            'avec_mail' => \App\Models\Fournisseur::whereNotNull('email')->count(),
        ];
    @endphp

    <div class="col-md-4">
        <div class="card card-corporate border-start border-primary border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Total Partenaires</span>
                <h3 class="fw-bold text-navy mb-0">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-corporate border-start border-success border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Statut Opérationnel</span>
                <h3 class="fw-bold text-navy mb-0">{{ $stats['actifs'] }} <small class="text-muted small">Actifs</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-corporate border-start border-slate border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Contacts Numériques</span>
                <h3 class="fw-bold text-navy mb-0">{{ $stats['avec_mail'] }} <small class="text-muted small">Emails</small></h3>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des Fournisseurs -->
<div class="card card-corporate border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">NOM DU FOURNISSEUR</th>
                        <th>COORDONNÉES</th>
                        <th>LOCALISATION</th>
                        <th class="text-center">STATUT</th>
                        <th class="pe-4 text-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fournisseurs as $fournisseur)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-sm bg-light-navy text-navy me-3 fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                                        {{ substr($fournisseur->nom, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold text-navy small">{{ $fournisseur->nom }}</p>
                                        <span class="badge badge-subtle font-2xs">{{ $fournisseur->code_fournisseur }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="small">
                                <div class="mb-1"><i class="bi bi-telephone text-secondary me-2"></i>{{ $fournisseur->telephone }}</div>
                                @if($fournisseur->email)
                                    <div><i class="bi bi-envelope text-secondary me-2"></i>{{ $fournisseur->email }}</div>
                                @endif
                            </td>
                            <td class="small text-secondary">
                                <i class="bi bi-geo-alt me-1"></i> {{ $fournisseur->adresse ?: 'Non spécifiée' }}
                            </td>
                            <td class="text-center">
                                @if ($fournisseur->statut === 'actif')
                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill px-3 font-2xs fw-bold">OPÉRATIONNEL</span>
                                @else
                                    <span class="badge bg-light text-secondary border rounded-pill px-3 font-2xs fw-bold">EN PAUSE</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn btn-sm btn-light border" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light border text-danger" title="Supprimer" onclick="confirmDelete('{{ route('fournisseurs.destroy', $fournisseur) }}', '{{ $fournisseur->nom }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-truck fs-2 d-block mb-2"></i>
                                Aucun fournisseur répertorié.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($fournisseurs->hasPages())
    <div class="d-flex justify-content-center mt-4 pb-4">
        {{ $fournisseurs->links() }}
    </div>
@endif

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete(url, name) {
        if (confirm('Retirer le fournisseur ' + name + ' de la base de données ?')) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection
