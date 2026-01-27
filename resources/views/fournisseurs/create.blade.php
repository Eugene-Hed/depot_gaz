@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="d-flex align-items-center gap-3">
                <i class="bi bi-plus-circle" style="font-size: 2rem; color: #6366f1;"></i>
                <span>Nouveau fournisseur</span>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('fournisseurs.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="nom" class="form-label fw-bold">
                                <i class="bi bi-building"></i> Nom du fournisseur
                            </label>
                            <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Ex: Butagaz Sénégal, Primagaz..." 
                                   value="{{ old('nom') }}" required>
                            @error('nom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="telephone" class="form-label fw-bold">
                                    <i class="bi bi-telephone"></i> Téléphone
                                </label>
                                <input type="tel" class="form-control form-control-lg @error('telephone') is-invalid @enderror" 
                                       id="telephone" name="telephone" placeholder="+221 77 123 45 67" 
                                       value="{{ old('telephone') }}" required>
                                @error('telephone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-bold">
                                    <i class="bi bi-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" name="email" placeholder="contact@fournisseur.com" 
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="adresse" class="form-label fw-bold">
                                <i class="bi bi-geo-alt"></i> Adresse
                            </label>
                            <textarea class="form-control form-control-lg @error('adresse') is-invalid @enderror" 
                                      id="adresse" name="adresse" rows="4" 
                                      placeholder="123 Rue de la Paix, Dakar">{{ old('adresse') }}</textarea>
                            @error('adresse')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-toggle-on"></i> Statut
                            </label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="statut" id="statut_actif" 
                                       value="actif" {{ old('statut') === 'actif' || !old('statut') ? 'checked' : '' }} required>
                                <label class="btn btn-outline-success" for="statut_actif">
                                    <i class="bi bi-check-circle"></i> Actif
                                </label>

                                <input type="radio" class="btn-check" name="statut" id="statut_inactif" 
                                       value="inactif" {{ old('statut') === 'inactif' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary" for="statut_inactif">
                                    <i class="bi bi-pause-circle"></i> Inactif
                                </label>
                            </div>
                            @error('statut')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        @error('error')
                            <div class="alert alert-danger mb-4" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> {{ $message }}
                            </div>
                        @enderror

                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-check-circle"></i> Créer le fournisseur
                            </button>
                            <a href="{{ route('fournisseurs.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-info-subtle">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-lightbulb"></i> Guide de remplissage
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <strong class="text-info"><i class="bi bi-building"></i> Nom</strong>
                            <p class="text-muted mb-0 small mt-1">Nom complet du fournisseur. Doit être unique.</p>
                        </li>
                        <li class="mb-3">
                            <strong class="text-info"><i class="bi bi-telephone"></i> Téléphone</strong>
                            <p class="text-muted mb-0 small mt-1">Numéro de contact principal du fournisseur.</p>
                        </li>
                        <li class="mb-3">
                            <strong class="text-info"><i class="bi bi-envelope"></i> Email</strong>
                            <p class="text-muted mb-0 small mt-1">Adresse email optionnelle pour la communication.</p>
                        </li>
                        <li class="mb-3">
                            <strong class="text-info"><i class="bi bi-geo-alt"></i> Adresse</strong>
                            <p class="text-muted mb-0 small mt-1">Lieu géographique de l'établissement du fournisseur.</p>
                        </li>
                        <li>
                            <strong class="text-info"><i class="bi bi-toggle-on"></i> Statut</strong>
                            <p class="text-muted mb-0 small mt-1">Actif = peut recevoir des commandes. Inactif = archivé.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
