@extends('layouts.app')

@section('title', 'Gestion des Marques')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Catalogue des Marques</h1>
        <p class="text-secondary small mb-0">Gestion des fabricants et partenaires de distribution</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('marques.create') }}" class="btn btn-navy rounded-pill px-4 btn-sm fw-bold">
            <i class="bi bi-patch-plus me-1"></i> Ajouter une Marque
        </a>
    </div>
</div>

<!-- Statistiques Rapides -->
<div class="row g-3 mb-5">
    @php
        $totalMarques = $marques->total();
        $marquesActives = $marques->filter(fn($m) => $m->statut === 'actif')->count();
        $totalTypes = $marques->sum(fn($m) => $m->typesBouteilles()->count());
    @endphp

    <div class="col-md-4">
        <div class="card card-corporate border-start border-primary border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Total Marques</span>
                <h3 class="fw-bold text-navy mb-0">{{ $totalMarques }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-corporate border-start border-success border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">En Partenariat</span>
                <h3 class="fw-bold text-navy mb-0">{{ $marquesActives }} <small class="text-muted small">Actives</small></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-corporate border-start border-slate border-4 shadow-sm h-100">
            <div class="card-body p-4">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Total Références</span>
                <h3 class="fw-bold text-navy mb-0">{{ $totalTypes }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Tableau des Marques -->
<div class="card card-corporate border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">NOM DE LA MARQUE</th>
                        <th class="text-center">TYPES DE BOUTEILLES</th>
                        <th class="text-center">STATUT</th>
                        <th class="text-center">ENREGISTREMENT</th>
                        <th class="pe-4 text-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($marques as $marque)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-sm bg-light-navy text-navy me-3 fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px; overflow: hidden;">
                                        @if($marque->image_url)
                                            <img src="{{ $marque->image_url }}" alt="{{ $marque->nom }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            {{ substr($marque->nom, 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="fw-bold text-navy">{{ $marque->nom }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-subtle px-3 py-2 rounded-pill font-2xs">{{ $marque->typesBouteilles()->count() }} types</span>
                            </td>
                            <td class="text-center">
                                @if ($marque->statut === 'actif')
                                    <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill px-3 font-2xs fw-bold">ACTIF</span>
                                @else
                                    <span class="badge bg-light text-secondary border rounded-pill px-3 font-2xs fw-bold">INACTIF</span>
                                @endif
                            </td>
                            <td class="text-center small text-secondary">
                                {{ $marque->created_at->format('d/m/Y') }}
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('marques.edit', $marque) }}" class="btn btn-sm btn-light border" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light border text-danger" title="Supprimer" onclick="confirmDelete('{{ route('marques.destroy', $marque) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-tag fs-2 d-block mb-2"></i>
                                Aucune marque enregistrée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($marques->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $marques->links() }}
    </div>
@endif

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete(url) {
        if (confirm('Supprimer cette marque et ses types associés ?')) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection
