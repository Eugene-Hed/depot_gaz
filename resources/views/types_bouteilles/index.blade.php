@extends('layouts.app')

@section('title', 'Catalogue des Produits')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Catalogue Produits</h1>
        <p class="text-secondary small mb-0">Gestion technique des types de bouteilles et tarification</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('types-bouteilles.create') }}" class="btn btn-navy rounded-pill px-4 btn-sm fw-bold">
            <i class="bi bi-plus-lg me-1"></i> Nouveau Produit
        </a>
    </div>
</div>

<!-- Statistiques Rapides -->
<div class="row g-3 mb-5">
    <div class="col-md-3">
        <div class="card card-corporate border-start border-primary border-4 shadow-sm h-100">
            <div class="card-body p-3">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Total Références</span>
                <h4 class="fw-bold text-navy mb-0">{{ $types->total() }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-corporate border-start border-success border-4 shadow-sm h-100">
            <div class="card-body p-3">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Types Actifs</span>
                <h4 class="fw-bold text-navy mb-0">{{ $stats['typesActifs'] }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-corporate border-start border-slate border-4 shadow-sm h-100">
            <div class="card-body p-3">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Prix Moyen Vente</span>
                <h4 class="fw-bold text-navy mb-0">{{ number_format($stats['prixMoyen'] ?? 0, 0, ',', ' ') }} <small class="text-muted small">F</small></h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-corporate border-start border-{{ $stats['alertesStock'] > 0 ? 'danger' : 'success' }} border-4 shadow-sm h-100">
            <div class="card-body p-3">
                <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Alertes Stock</span>
                <h4 class="fw-bold text-{{ $stats['alertesStock'] > 0 ? 'danger' : 'navy' }} mb-0">{{ $stats['alertesStock'] }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Filtres Corporatifs -->
<div class="card card-corporate border-0 shadow-sm mb-5">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('types-bouteilles.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="small text-muted fw-bold text-uppercase mb-1">Recherche</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Nom, taille, marque..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="small text-muted fw-bold text-uppercase mb-1">Statut</label>
                <select name="statut" class="form-select form-select-sm">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actifs</option>
                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactifs</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="small text-muted fw-bold text-uppercase mb-1">Filtrer Stock</label>
                <select name="alerte" class="form-select form-select-sm">
                    <option value="">Niveau global</option>
                    <option value="oui" {{ request('alerte') == 'oui' ? 'selected' : '' }}>En alerte</option>
                    <option value="non" {{ request('alerte') == 'non' ? 'selected' : '' }}>Normal</option>
                </select>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group">
                    <button type="submit" class="btn btn-navy px-4 btn-sm fw-bold">Appliquer</button>
                    <a href="{{ route('types-bouteilles.index') }}" class="btn btn-light px-3 btn-sm border">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Grille des Produits -->
@if($types->count() > 0)
    <div class="row g-4 mb-5">
        @foreach ($types as $type)
            @php
                $stock = $type->stock;
                $quantite = $stock ? $stock->quantite_pleine : 0;
                $isLow = $quantite < $type->seuil_alerte;
                $percentage = $type->seuil_alerte > 0 ? min(($quantite / ($type->seuil_alerte * 2)) * 100, 100) : 0;
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm card-corporate ranking-item overflow-hidden">
                    <!-- Image Area -->
                    <div class="position-relative bg-light-navy p-4 d-flex align-items-center justify-content-center" style="height: 180px;">
                        <img src="{{ $type->image_url }}" alt="{{ $type->taille }}" class="bottle-zoom" style="max-height: 100%; object-fit: contain;">
                        
                        <div class="position-absolute top-0 start-0 p-3">
                            @if ($type->statut === 'actif')
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 rounded-pill px-2 font-2xs fw-bold">ACTIF</span>
                            @else
                                <span class="badge bg-light text-secondary border rounded-pill px-2 font-2xs fw-bold">INACTIF</span>
                            @endif
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <span class="text-secondary fw-bold text-uppercase font-2xs ls-1">{{ $type->marque->nom ?? 'Standard' }}</span>
                            <h5 class="fw-bold text-navy mb-0 mt-1">{{ $type->taille }}</h5>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <small class="text-muted font-2xs d-block">Prix de Vente</small>
                                <span class="fw-bold text-navy">{{ number_format($type->prix_vente, 0, ',', ' ') }} F</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted font-2xs d-block">Stock Actuel</small>
                                <span class="fw-bold {{ $isLow ? 'text-danger' : 'text-success' }}">{{ $quantite }} unités</span>
                            </div>
                        </div>

                        <div class="progress mb-4" style="height: 4px; background: #f1f5f9;">
                            <div class="progress-bar {{ $isLow ? 'bg-danger' : 'bg-navy' }}" style="width: {{ $percentage }}%"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('types-bouteilles.edit', $type) }}" class="btn btn-sm btn-light border flex-grow-1 font-2xs fw-bold text-uppercase">
                                <i class="bi bi-pencil me-1"></i> Éditer
                            </a>
                            <button type="button" class="btn btn-sm btn-light border text-danger font-2xs fw-bold text-uppercase px-3" onclick="confirmDelete('{{ route('types-bouteilles.destroy', $type) }}', '{{ $type->taille }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($types->hasPages())
        <div class="d-flex justify-content-center mb-5">
            {{ $types->links() }}
        </div>
    @endif
@else
    <div class="card card-corporate border-0 shadow-sm py-5">
        <div class="card-body text-center">
            <i class="bi bi-box-seam fs-1 text-muted opacity-25 d-block mb-3"></i>
            <h5 class="text-navy fw-bold">Aucun produit configuré</h5>
            <p class="text-secondary small">Veuillez ajouter des types de bouteilles pour commencer la gestion.</p>
            <a href="{{ route('types-bouteilles.create') }}" class="btn btn-navy rounded-pill px-4 mt-2">Démarrer le catalogue</a>
        </div>
    </div>
@endif

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete(url, name) {
        if (confirm('Confirmer la suppression du produit ' + name + ' ?')) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    }
</script>

<style>
    .bg-light-navy { background-color: rgba(15, 23, 42, 0.02); }
    .bottle-zoom { transition: transform 0.3s ease; }
    .ranking-item:hover .bottle-zoom { transform: scale(1.1); }
    .ls-1 { letter-spacing: 0.5px; }
    .btn-navy { background-color: #0f172a; color: white; border: none; }
    .btn-navy:hover { background-color: #1e293b; color: white; }
</style>
@endsection
