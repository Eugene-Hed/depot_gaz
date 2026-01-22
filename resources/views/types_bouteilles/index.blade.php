@extends('layouts.app')

@section('title', 'Types de bouteilles')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">
                <i class="bi bi-box-seam text-primary"></i> Types de bouteilles
            </h1>
            <p class="text-muted mb-0">Gestion des types de bouteilles et leurs tarifs</p>
        </div>
        <a href="{{ route('types-bouteilles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouveau type
        </a>
    </div>

    <!-- Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques KPI -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total types</p>
                            <h3 class="mb-0 fw-bold">{{ $types->total() }}</h3>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-box-seam text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Types actifs</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['typesActifs'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Prix moyen</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['prixMoyen'] ?? 0, 0, ',', ' ') }} F</h3>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="bi bi-tag text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 kpi-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Alertes stock</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['alertesStock'] }}</h3>
                        </div>
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                            <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('types-bouteilles.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Rechercher</label>
                    <input type="text" name="search" class="form-control" placeholder="Taille, marque..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Alerte stock</label>
                    <select name="alerte" class="form-select">
                        <option value="">Tous</option>
                        <option value="oui" {{ request('alerte') == 'oui' ? 'selected' : '' }}>En alerte</option>
                        <option value="non" {{ request('alerte') == 'non' ? 'selected' : '' }}>Normal</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                        <a href="{{ route('types-bouteilles.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des types en cards -->
    @if($types->count() > 0)
        <div class="row g-4 mb-4">
            @foreach ($types as $type)
                @php
                    $stock = $type->stock;
                    $quantite = $stock ? $stock->quantite_pleine : 0;
                    $isLow = $quantite < $type->seuil_alerte;
                    $percentage = $type->seuil_alerte > 0 ? min(($quantite / ($type->seuil_alerte * 2)) * 100, 100) : 0;
                    $barColor = $percentage > 50 ? 'success' : ($percentage > 25 ? 'warning' : 'danger');
                @endphp
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm type-card {{ $isLow ? 'border-danger' : '' }}" 
                         style="{{ $isLow ? 'border: 2px solid var(--bs-danger) !important;' : '' }}">
                        
                        <!-- Image -->
                        <div class="position-relative" style="height: 220px; background: #f8f9fa; overflow: hidden;">
                            <img src="{{ $type->image_url }}" 
                                 alt="{{ $type->marque->nom ?? 'Type' }} {{ $type->taille }}" 
                                 class="position-absolute top-50 start-50 translate-middle bottle-image"
                                 style="max-width: 85%; max-height: 85%; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                            
                            <!-- Badges -->
                            <div class="position-absolute top-0 start-0 m-2">
                                @if ($type->statut === 'actif')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i> Actif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle-fill"></i> Inactif
                                    </span>
                                @endif
                            </div>

                            @if($isLow)
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-danger">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Stock bas
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Corps -->
                        <div class="card-body">
                            <!-- En-tête -->
                            <div class="mb-3">
                                <span class="badge bg-secondary mb-2">{{ $type->marque->nom ?? 'Sans marque' }}</span>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 fw-bold">{{ $type->taille }}</h5>
                                    <div class="text-end">
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Prix vente</small>
                                        <h5 class="text-primary mb-0 fw-bold">{{ number_format($type->prix_vente, 0, ',', ' ') }} F</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- Détails prix -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded text-center">
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Recharge</small>
                                        <strong class="text-dark d-block">{{ number_format($type->prix_recharge ?? 0, 0, ',', ' ') }} F</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 {{ $isLow ? 'bg-danger' : 'bg-light' }} rounded text-center">
                                        <small class="{{ $isLow ? 'text-white' : 'text-muted' }} d-block" style="font-size: 0.7rem;">Seuil</small>
                                        <strong class="{{ $isLow ? 'text-white' : 'text-dark' }} d-block">
                                            {{ $type->seuil_alerte }}
                                            @if($isLow) <i class="bi bi-exclamation-triangle-fill"></i> @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Stock disponible</small>
                                    <small class="fw-bold {{ $isLow ? 'text-danger' : 'text-success' }}">
                                        {{ $quantite }} bouteilles
                                    </small>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $barColor }}" 
                                         role="progressbar" 
                                         style="width: {{ $percentage }}%"
                                         aria-valuenow="{{ $percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card-footer bg-white border-top">
                            <div class="d-grid gap-2">
                                <a href="{{ route('types-bouteilles.edit', $type) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i> Modifier
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger btn-sm" 
                                        onclick="confirmDelete('{{ route('types-bouteilles.destroy', $type) }}', '{{ $type->marque->nom ?? '' }} {{ $type->taille }}')">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($types->hasPages())
            <div class="d-flex justify-content-center">
                {{ $types->links() }}
            </div>
        @endif
    @else
        <!-- État vide -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
                <h4 class="text-muted mb-3">Aucun type de bouteille</h4>
                <p class="text-muted mb-4">Commencez par créer votre premier type de bouteille pour gérer votre stock</p>
                <a href="{{ route('types-bouteilles.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Créer un type
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-danger"></i> Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Êtes-vous sûr de vouloir supprimer <strong id="deleteTypeName"></strong> ?</p>
                <p class="text-muted small mb-0 mt-2">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animation des KPI cards */
    .kpi-card {
        transition: all 0.3s ease;
    }
    
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Animation des type cards */
    .type-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    
    .type-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2) !important;
    }

    /* Images des bouteilles - prévention du clignotement */
    .bottle-image {
        background: transparent;
        transition: transform 0.3s ease;
    }

    /* Animation de l'image au survol */
    .type-card:hover .bottle-image {
        transform: translate(-50%, -50%) scale(1.05);
    }

    /* Progress bar animation */
    .progress-bar {
        transition: width 0.6s ease;
    }

    /* Boutons dans le footer */
    .card-footer .btn {
        transition: all 0.2s ease;
    }

    .card-footer .btn:hover {
        transform: translateY(-2px);
    }

    /* Badge animations */
    .badge {
        transition: all 0.2s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .type-card:hover {
            transform: translateY(-4px);
        }
    }

    /* Amélioration de l'apparence des cards */
    .card {
        border-radius: 0.75rem;
    }

    .type-card .card-body {
        padding: 1.25rem;
    }

    .type-card .card-footer {
        padding: 1rem 1.25rem;
        border-radius: 0 0 0.75rem 0.75rem;
    }

    /* Style des filtres */
    .form-label.small {
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>

<script>
    // Fonction de confirmation de suppression avec modal Bootstrap
    function confirmDelete(url, typeName) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('deleteTypeName').textContent = typeName;
        document.getElementById('deleteForm').action = url;
        modal.show();
    }

    // Auto-dismiss des alertes après 5 secondes
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endsection
