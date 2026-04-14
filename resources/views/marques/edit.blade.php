@extends('layouts.app')

@section('title', 'Modifier Marque - ' . $marque->nom)

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Profil Industriel</h1>
        <p class="text-secondary small mb-0">Mise à jour des informations de la marque {{ $marque->nom }}</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('marques.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-corporate border-0 shadow-sm">
            <div class="card-header bg-navy text-white p-4">
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-pencil-square me-2"></i> Modification de l'Identité</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('marques.update', $marque) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Nom -->
                        <div class="col-md-12">
                            <label for="nom" class="small text-muted fw-bold text-uppercase mb-2 d-block">Nom Commercial *</label>
                            <input type="text" class="form-control form-control-lg fw-bold text-navy @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom', $marque->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div class="col-md-12">
                            <label for="image" class="small text-muted fw-bold text-uppercase mb-2 d-block">Logo de la Marque</label>
                            <div class="p-4 border border-dashed rounded-4 text-center bg-light">
                                <input type="file" class="form-control d-none @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                <label for="image" class="cursor-pointer mb-0">
                                    @if($marque->image)
                                        <div class="mb-3">
                                            <img src="{{ $marque->image_url }}" alt="{{ $marque->nom }}" class="rounded shadow-sm border p-2 bg-white" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                                            <p class="font-2xs text-navy fw-bold mt-2 mb-0">Logo actuel</p>
                                        </div>
                                    @else
                                        <div class="mb-2">
                                            <i class="bi bi-cloud-arrow-up fs-2 text-navy opacity-50"></i>
                                        </div>
                                    @endif
                                    <span class="d-block fw-bold text-navy small">Cliquer pour remplacer le logo</span>
                                    <span class="text-muted font-2xs">SVG, PNG ou JPG (Max: 2MB)</span>
                                </label>
                                <div class="mt-3">
                                    <img id="preview" src="" alt="Aperçu" class="rounded shadow-sm" style="display: none; max-width: 100px; max-height: 100px; margin: 0 auto;">
                                </div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="col-md-12">
                            <label class="small text-muted fw-bold text-uppercase mb-2 d-block">Visibilité</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="statut" id="statut_actif" 
                                       value="actif" {{ old('statut', $marque->statut) === 'actif' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-navy py-2 fw-bold" for="statut_actif">Active</label>

                                <input type="radio" class="btn-check" name="statut" id="statut_inactif" 
                                       value="inactif" {{ old('statut', $marque->statut) === 'inactif' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-navy py-2 fw-bold" for="statut_inactif">Inactive</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Appliquer les Changements
                        </button>
                        <button type="button" class="btn btn-light rounded-pill px-4 border text-danger small fw-bold ms-auto" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-corporate border-0 shadow-sm bg-light h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-navy text-uppercase ls-wide small mb-4">Informations Audit</h6>
                
                <div class="small text-secondary">
                    <div class="mb-4">
                        <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Impact Catalogue</span>
                        <h6 class="fw-bold text-navy mb-0 small">{{ $marque->typesBouteilles()->count() }} produits associés</h6>
                    </div>

                    <div class="mb-4 text-start">
                        <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Historique Modification</span>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Créée le</span>
                            <span class="fw-medium text-navy">{{ $marque->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Dernier Edit</span>
                            <span class="fw-medium text-navy">{{ $marque->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded-4 border border-navy border-opacity-10 mb-4">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        <span class="font-2xs fw-bold text-navy">NOTE :</span>
                        <p class="font-2xs mb-0 mt-1">Si vous rendez une marque inactive, ses produits ne seront plus proposés lors des nouvelles transactions, mais resteront visibles dans les anciens rapports.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white border-0 p-4">
                <h5 class="modal-title fw-bold text-uppercase ls-wide small"><i class="bi bi-exclamation-triangle-fill me-2"></i> Danger zone</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3 text-navy fw-bold">Supprimer la marque :</p>
                <div class="bg-light p-3 rounded-4 mb-4 border text-center">
                    <img src="{{ $marque->image_url }}" alt="" class="mb-2" style="max-height: 40px;">
                    <h6 class="mb-0 fw-bold text-navy">{{ $marque->nom }}</h6>
                </div>
                <p class="text-muted small mb-0"><strong>Attention :</strong> La suppression d'une marque peut entraîner des erreurs si des produits ou des transactions y sont encore rattachés. Préférez désactiver la marque si vous souhaitez simplement ne plus l'utiliser.</p>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold font-2xs text-uppercase" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('marques.destroy', $marque) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold font-2xs text-uppercase">Confirmer la suppression</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
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
    .cursor-pointer { cursor: pointer; }
    .border-dashed { border-style: dashed !important; border-width: 2px !important; }
</style>
@endsection
