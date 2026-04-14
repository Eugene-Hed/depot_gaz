@extends('layouts.app')

@section('title', 'Nouvelle Marque')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Enregistrer une Marque</h1>
        <p class="text-secondary small mb-0">Définition d'un nouvel industriel partenaire</p>
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
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-patch-check me-2"></i> Identité Marque</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('marques.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Nom -->
                        <div class="col-md-12">
                            <label for="nom" class="small text-muted fw-bold text-uppercase mb-2 d-block">Nom Commercial *</label>
                            <input type="text" class="form-control form-control-lg fw-bold text-navy @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Ex: TotalEnergies, Butagaz..." 
                                   value="{{ old('nom') }}" required autofocus>
                            @error('nom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Image/Logo -->
                        <div class="col-md-12">
                            <label for="image" class="small text-muted fw-bold text-uppercase mb-2 d-block">Vignette / Logo</label>
                            <div class="p-4 border border-dashed rounded-4 text-center bg-light">
                                <input type="file" class="form-control d-none @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                <label for="image" class="cursor-pointer mb-0">
                                    <div class="mb-2">
                                        <i class="bi bi-cloud-arrow-up fs-2 text-navy opacity-50"></i>
                                    </div>
                                    <span class="d-block fw-bold text-navy small">Cliquer pour télécharger le logo</span>
                                    <span class="text-muted font-2xs">SVG, PNG ou JPG (Max: 2MB)</span>
                                </label>
                                <div class="mt-3">
                                    <img id="preview" src="" alt="Aperçu" class="rounded shadow-sm" style="display: none; max-width: 120px; max-height: 120px; margin: 0 auto;">
                                </div>
                            </div>
                            @error('image')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div class="col-md-12">
                            <label class="small text-muted fw-bold text-uppercase mb-2 d-block">Disponibilité</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="statut" id="statut_actif" 
                                       value="actif" {{ old('statut', 'actif') === 'actif' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-navy py-2" for="statut_actif">Marque Active</label>

                                <input type="radio" class="btn-check" name="statut" id="statut_inactif" 
                                       value="inactif" {{ old('statut') === 'inactif' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-navy py-2" for="statut_inactif">Inactive</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Enregistrer la Marque
                        </button>
                        <a href="{{ route('marques.index') }}" class="btn btn-light rounded-pill px-4 border small fw-bold">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-corporate border-0 shadow-sm bg-light h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-navy text-uppercase ls-wide small mb-4">Aide contextuelle</h6>
                <div class="small text-secondary">
                    <p class="mb-3"><strong>Unique :</strong> Le nom de la marque sert de référence unique. On ne peut pas avoir deux marques avec le même nom.</p>
                    <p class="mb-4"><strong>Audit :</strong> Une marque inactive ne pourra plus être sélectionnée lors de la création de nouveaux types de bouteilles, mais ses données historiques seront conservées.</p>
                    
                    <div class="bg-white p-3 rounded-4 border border-navy border-opacity-10">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        <span class="font-2xs fw-bold text-navy">INFO :</span>
                        <p class="font-2xs mb-0 mt-1">L'image sera automatiquement redimensionnée pour s'adapter à l'affichage des vignettes du catalogue.</p>
                    </div>
                </div>
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
