@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-0">
                <i class="bi bi-pencil-square"></i> Modifier marque
            </h1>
            <p class="text-muted">Mettre à jour les informations de la marque</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Form Column (Left - 8 cols) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('marques.update', $marque) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nom -->
                        <div class="mb-4">
                            <label for="nom" class="form-label fw-bold">
                                <i class="bi bi-tag"></i> Nom de la marque
                            </label>
                            <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Ex: TotalEnergies, Butagaz..." 
                                   value="{{ old('nom', $marque->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image/Logo -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">
                                <i class="bi bi-image"></i> Logo de la marque
                            </label>
                            
                            @if($marque->image)
                                <div class="mb-2">
                                    <img src="{{ $marque->image_url }}" alt="{{ $marque->nom }}" class="rounded border" style="max-width: 150px; max-height: 150px; object-fit: contain;">
                                    <p class="small text-muted mt-1">Logo actuel</p>
                                </div>
                            @endif
                            
                            <input type="file" class="form-control form-control-lg @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Format: JPG, PNG, GIF, SVG. Taille max: 2MB. Laisser vide pour conserver l'image actuelle.
                            </small>
                            <div class="mt-2">
                                <img id="preview" src="" alt="Aperçu" class="rounded" style="display: none; max-width: 150px; max-height: 150px;">
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">
                                <i class="bi bi-toggle-on"></i> Statut
                            </label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="statut" id="statut_actif" 
                                           value="actif" {{ old('statut', $marque->statut) === 'actif' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="statut_actif">
                                        <span class="badge bg-success">Actif</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="statut" id="statut_inactif" 
                                           value="inactif" {{ old('statut', $marque->statut) === 'inactif' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="statut_inactif">
                                        <span class="badge bg-secondary">Inactif</span>
                                    </label>
                                </div>
                            </div>
                            @error('statut')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        @error('error')
                            <div class="alert alert-danger mb-4" role="alert">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 pt-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg"></i> Mettre à jour
                            </button>
                            <a href="{{ route('marques.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-lg"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar (Right - 4 cols) -->
        <div class="col-lg-4">
            <!-- Current Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Informations actuelles
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row g-3 mb-0">
                        <dt class="col-sm-5 fw-bold text-muted">Nom:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-light text-dark">{{ $marque->nom }}</span>
                        </dd>

                        <dt class="col-sm-5 fw-bold text-muted">Statut:</dt>
                        <dd class="col-sm-7">
                            @if ($marque->statut === 'actif')
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </dd>

                        <dt class="col-sm-5 fw-bold text-muted">Types:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-info">{{ $marque->typesBouteilles()->count() }}</span>
                        </dd>

                        <dt class="col-sm-5 fw-bold text-muted">Créée le:</dt>
                        <dd class="col-sm-7 text-muted small">{{ $marque->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-5 fw-bold text-muted">Mise à jour:</dt>
                        <dd class="col-sm-7 text-muted small">{{ $marque->updated_at->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger border-2 shadow-sm">
                <div class="card-header bg-danger bg-opacity-10 border-danger">
                    <h5 class="card-title mb-0 text-danger">
                        <i class="bi bi-exclamation-triangle"></i> Zone dangereuse
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        Cette action est irréversible. Supprimez cette marque de manière permanente.
                    </p>
                    <form id="deleteForm" action="{{ route('marques.destroy', $marque) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="btn btn-danger w-100" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i> Supprimer cette marque
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-danger bg-danger bg-opacity-10">
                <h5 class="modal-title text-danger" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle"></i> Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">
                    Êtes-vous sûr de vouloir supprimer la marque 
                    <strong>{{ $marque->nom }}</strong> ?
                </p>
                <p class="text-danger small mt-2">
                    ⚠️ Cette action est irréversible et supprimera toutes les données associées.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i> Annuler
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteMarque()">
                    <i class="bi bi-trash"></i> Supprimer définitivement
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    function deleteMarque() {
        document.getElementById('deleteForm').submit();
    }

    // Aperçu de l'image
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>

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
