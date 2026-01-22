@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <h1 class="mb-0">
                <i class="bi bi-pencil-square"></i> Modifier type de bouteille
            </h1>
            <p class="text-muted">Mettre à jour les informations du type</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Form Column (Left - 8 cols) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('types-bouteilles.update', $typeBouteille) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Marque et Taille -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="id_marque" class="form-label fw-bold">
                                    <i class="bi bi-box"></i> Marque
                                </label>
                                <select class="form-select form-select-lg @error('id_marque') is-invalid @enderror" 
                                        id="id_marque" name="id_marque" required>
                                    <option value="">-- Sélectionner une marque --</option>
                                    @foreach ($marques as $marque)
                                        <option value="{{ $marque->id }}" 
                                                {{ old('id_marque', $typeBouteille->marque_id) == $marque->id ? 'selected' : '' }}>
                                            {{ $marque->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_marque')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="taille" class="form-label fw-bold">
                                    <i class="bi bi-rulers"></i> Taille (litres)
                                </label>
                                <input type="text" class="form-control form-control-lg @error('taille') is-invalid @enderror" 
                                       id="taille" name="taille" placeholder="Ex: 12, 5, 2.5" 
                                       value="{{ old('taille', $typeBouteille->taille) }}" required>
                                @error('taille')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">
                                <i class="bi bi-image"></i> Image de la bouteille
                            </label>
                            @if($typeBouteille->image)
                                <div class="mb-2">
                                    <img src="{{ $typeBouteille->image_url }}" alt="Image actuelle" 
                                         class="rounded border" style="max-width: 150px; max-height: 150px;">
                                    <p class="text-muted small mt-1">Image actuelle</p>
                                </div>
                            @endif
                            <input type="file" class="form-control form-control-lg @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Format: JPG, PNG, GIF. Taille max: 2MB. Laissez vide pour conserver l'image actuelle.
                            </small>
                            <div class="mt-2">
                                <img id="preview" src="" alt="Aperçu" class="rounded border" style="display: none; max-width: 200px; max-height: 200px;">
                            </div>
                        </div>

                        <!-- Seuil d'alerte -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="seuil_alerte" class="form-label fw-bold">
                                    <i class="bi bi-exclamation-triangle"></i> Seuil d'alerte (qty)
                                </label>
                                <input type="number" class="form-control form-control-lg @error('seuil_alerte') is-invalid @enderror" 
                                       id="seuil_alerte" name="seuil_alerte" 
                                       value="{{ old('seuil_alerte', $typeBouteille->seuil_alerte) }}" min="0" required>
                                @error('seuil_alerte')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Prices Row -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="prix_vente" class="form-label fw-bold">
                                    <i class="bi bi-tag"></i> Prix vente (FCFA)
                                </label>
                                <input type="number" class="form-control form-control-lg @error('prix_vente') is-invalid @enderror" 
                                       id="prix_vente" name="prix_vente" placeholder="0" step="100"
                                       value="{{ old('prix_vente', $typeBouteille->prix_vente) }}" required>
                                @error('prix_vente')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="prix_recharge" class="form-label fw-bold">
                                    <i class="bi bi-lightning-charge"></i> Prix recharge (FCFA)
                                </label>
                                <input type="number" class="form-control form-control-lg @error('prix_recharge') is-invalid @enderror" 
                                       id="prix_recharge" name="prix_recharge" placeholder="0" step="100"
                                       value="{{ old('prix_recharge', $typeBouteille->prix_recharge) }}" required>
                                @error('prix_recharge')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">
                                <i class="bi bi-toggle-on"></i> Statut
                            </label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="statut" id="statut-actif" 
                                       value="actif" {{ old('statut', $typeBouteille->statut) === 'actif' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="statut-actif">
                                    <span class="badge bg-success">Actif</span>
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="statut" id="statut-inactif" 
                                       value="inactif" {{ old('statut', $typeBouteille->statut) === 'inactif' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="statut-inactif">
                                    <span class="badge bg-secondary">Inactif</span>
                                </label>
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
                            <a href="{{ route('types-bouteilles.index') }}" class="btn btn-outline-secondary btn-lg">
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
                        <dt class="col-sm-5 fw-bold text-muted">Marque:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-info">{{ $typeBouteille->marque->nom ?? 'N/A' }}</span>
                        </dd>

                        <dt class="col-sm-5 fw-bold text-muted">Taille:</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-light text-dark">{{ $typeBouteille->taille }}L</span>
                        </dd>

                        <dt class="col-sm-5 fw-bold text-muted">Statut:</dt>
                        <dd class="col-sm-7">
                            @if ($typeBouteille->statut === 'actif')
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </dd>

                        <dt class="col-sm-5 fw-bold text-muted">Créé le:</dt>
                        <dd class="col-sm-7 text-muted small">{{ $typeBouteille->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-5 fw-bold text-muted">Mis à jour:</dt>
                        <dd class="col-sm-7 text-muted small">{{ $typeBouteille->updated_at->format('d/m/Y H:i') }}</dd>
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
                        Cette action est irréversible. Supprimez ce type de bouteille de manière permanente.
                    </p>
                    <form id="deleteForm" action="{{ route('types-bouteilles.destroy', $typeBouteille) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    <button type="button" class="btn btn-danger w-100" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i> Supprimer ce type
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
                    Êtes-vous sûr de vouloir supprimer le type de bouteille 
                    <strong>{{ $typeBouteille->marque->nom }} {{ $typeBouteille->taille }}L</strong> ?
                </p>
                <p class="text-danger small mt-2">
                    ⚠️ Cette action est irréversible et supprimera toutes les données associées.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i> Annuler
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteType()">
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

    function deleteType() {
        document.getElementById('deleteForm').submit();
    }

    // Aperçu de l'image
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
