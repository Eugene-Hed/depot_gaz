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

                    <form action="{{ route('types-bouteilles.store') }}" method="POST" enctype="multipart/form-data">
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

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="form-label fw-bold">
                                <i class="bi bi-image"></i> Image de la bouteille
                            </label>
                            <input type="file" class="form-control form-control-lg @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Format: JPG, PNG, GIF. Taille max: 2MB
                            </small>
                            <div class="mt-2">
                                <img id="preview" src="" alt="Aperçu" class="rounded" style="display: none; max-width: 200px; max-height: 200px;">
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
                                <label for="prix_consigne" class="form-label fw-bold">
                                    <i class="bi bi-shield-check"></i> Prix Consigne / Bouteille Vide (FCFA) *
                                </label>
                                <input type="number" class="form-control form-control-lg @error('prix_consigne') is-invalid @enderror" 
                                       id="prix_consigne" name="prix_consigne" placeholder="0" step="1"
                                       value="{{ old('prix_consigne') }}" required onchange="calculerPrixPleine()" oninput="calculerPrixPleine()">
                                @error('prix_consigne')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block mt-2">Prix d'achat du fer (bouteille vide)</small>
                            </div>

                            <div class="col-md-6">
                                <label for="prix_recharge" class="form-label fw-bold">
                                    <i class="bi bi-lightning-charge"></i> Prix Recharge (FCFA) *
                                </label>
                                <input type="number" class="form-control form-control-lg @error('prix_recharge') is-invalid @enderror" 
                                       id="prix_recharge" name="prix_recharge" placeholder="0" step="1"
                                       value="{{ old('prix_recharge') }}" required onchange="calculerPrixPleine()" oninput="calculerPrixPleine()">
                                @error('prix_recharge')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block mt-2">Échange vide contre pleine (même type)</small>
                            </div>
                        </div>

                        <!-- Prix Bouteille Pleine (auto-calculé) -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="prix_pleine_display" class="form-label fw-bold">
                                    <i class="bi bi-tag-fill"></i> Prix Bouteille Pleine (FCFA)
                                </label>
                                <input type="text" class="form-control form-control-lg bg-light fw-bold text-success" 
                                       id="prix_pleine_display" readonly value="0 FCFA">
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-calculator"></i> Calculé automatiquement : Consigne + Recharge
                                </small>
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

                        @error('error')
                            <div class="alert alert-danger mb-4" role="alert">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror

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
                        <p class="mb-2"><strong class="text-primary">Prix Consigne</strong></p>
                        <p class="small text-muted">Prix d'achat de la bouteille vide (le fer). C'est la caution que le client paie pour le corps de la bouteille.</p>
                        <div class="alert alert-light small mt-2">
                            Ex: 10 000 FCFA pour un fer de 12.5kg
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <p class="mb-2"><strong class="text-warning">Prix Recharge</strong></p>
                        <p class="small text-muted">Prix pour remplir une bouteille vide. Le client échange sa bouteille vide contre une pleine du même type.</p>
                        <div class="alert alert-light small mt-2">
                            Ex: 3 500 FCFA pour recharger
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-success small">
                        <i class="bi bi-calculator"></i>
                        <strong>Prix Bouteille Pleine</strong> = Consigne + Recharge<br>
                        <span class="text-muted">Calculé automatiquement. C'est le prix qu'un client sans bouteille paie pour repartir avec une bouteille pleine.</span>
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

    <script>
        // Calcul automatique du prix bouteille pleine
        function calculerPrixPleine() {
            const consigne = parseFloat(document.getElementById('prix_consigne').value) || 0;
            const recharge = parseFloat(document.getElementById('prix_recharge').value) || 0;
            const total = consigne + recharge;
            document.getElementById('prix_pleine_display').value = total.toLocaleString('fr-FR') + ' FCFA';
        }

        // Calculer au chargement de la page
        document.addEventListener('DOMContentLoaded', calculerPrixPleine);

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
