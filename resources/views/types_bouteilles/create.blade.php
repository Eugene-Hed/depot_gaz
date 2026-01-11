@extends('layouts.app')

@section('title', 'Nouveau type de bouteille')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-plus-circle"></i> Nouveau type de bouteille</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('types-bouteilles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-form-check"></i> Informations du type</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle-fill"></i> <strong>Erreur!</strong> Veuillez corriger les erreurs ci-dessous.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('types-bouteilles.store') }}" method="POST">
                        @csrf

                        <!-- Marque et Taille -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_marque" class="form-label fw-bold">
                                    <i class="bi bi-diagram-3"></i> Marque *
                                </label>
                                <select class="form-select form-select-lg @error('id_marque') is-invalid @enderror" 
                                        id="id_marque" name="id_marque" required>
                                    <option value="">-- Sélectionner une marque --</option>
                                    @foreach ($marques as $marque)
                                        <option value="{{ $marque->id }}" 
                                                {{ old('id_marque') == $marque->id ? 'selected' : '' }}>
                                            {{ $marque->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_marque')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="taille" class="form-label fw-bold">
                                    <i class="bi bi-rulers"></i> Taille (litres) *
                                </label>
                                <input type="text" class="form-control form-control-lg @error('taille') is-invalid @enderror" 
                                       id="taille" name="taille" placeholder="Ex: 12, 5, 2.5" 
                                       value="{{ old('taille') }}" required>
                                @error('taille')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Seuil d'alerte -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="seuil_alerte" class="form-label fw-bold">
                                    <i class="bi bi-exclamation-triangle"></i> Seuil d'alerte (quantité) *
                                </label>
                                <input type="number" class="form-control form-control-lg @error('seuil_alerte') is-invalid @enderror" 
                                       id="seuil_alerte" name="seuil_alerte" 
                                       value="{{ old('seuil_alerte', 10) }}" min="0" required>
                                @error('seuil_alerte')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block mt-2">Quantité minimale avant alerte</small>
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="prix_vente" class="form-label fw-bold">
                                    <i class="bi bi-tag"></i> Prix vente bouteille pleine (FCFA) *
                                </label>
                                <input type="number" class="form-control form-control-lg @error('prix_vente') is-invalid @enderror" 
                                       id="prix_vente" name="prix_vente" placeholder="0" step="100"
                                       value="{{ old('prix_vente') }}" required>
                                @error('prix_vente')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block mt-2">Prix de vente au client</small>
                            </div>

                            <div class="col-md-6">
                                <label for="prix_recharge" class="form-label fw-bold">
                                    <i class="bi bi-lightning-charge"></i> Prix recharge (FCFA) *
                                </label>
                                <input type="number" class="form-control form-control-lg @error('prix_recharge') is-invalid @enderror" 
                                       id="prix_recharge" name="prix_recharge" placeholder="0" step="100"
                                       value="{{ old('prix_recharge') }}" required>
                                @error('prix_recharge')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block mt-2">Vide contre pleine (même type)</small>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="mb-4">
                            <label for="statut" class="form-label fw-bold">
                                <i class="bi bi-toggle-on"></i> Statut *
                            </label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="statut" id="statut_actif" 
                                           value="actif" {{ old('statut', 'actif') === 'actif' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statut_actif">
                                        <i class="bi bi-check-circle"></i> Actif
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="statut" id="statut_inactif" 
                                           value="inactif" {{ old('statut') === 'inactif' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statut_inactif">
                                        <i class="bi bi-x-circle"></i> Inactif
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Créer le type
                            </button>
                            <a href="{{ route('types-bouteilles.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel informatif -->
        <div class="col-md-4">
            <div class="card shadow-sm bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Guide des prix</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="mb-2"><strong class="text-primary">Prix vente</strong></p>
                        <p class="small text-muted">Prix de vente de la bouteille pleine au client</p>
                        <div class="alert alert-light small mt-2">
                            Ex: 5 000 FCFA pour une bouteille de gaz
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <p class="mb-2"><strong class="text-warning">Prix recharge</strong></p>
                        <p class="small text-muted">Coût pour échanger une bouteille vide contre une bouteille pleine du même type</p>
                        <div class="alert alert-light small mt-2">
                            Ex: 3 500 FCFA pour recharger
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info small">
                        <i class="bi bi-lightbulb"></i>
                        <strong>Note:</strong> La différence entre prix vente et prix recharge représente la marge pour l'équipement initial.
                    </div>
                </div>
            </div>
        </div>
    </div>

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
