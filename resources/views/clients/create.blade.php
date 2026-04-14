@extends('layouts.app')

@section('title', 'Nouveau Client')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Inscrire un Client</h1>
        <p class="text-secondary small mb-0">Création d'une fiche client pour le suivi de fidélité</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('clients.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-corporate border-0 shadow-sm">
            <div class="card-header bg-navy text-white p-4">
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-person-plus me-2"></i> Informations Client</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <!-- Nom -->
                        <div class="col-md-12">
                            <label for="nom" class="small text-muted fw-bold text-uppercase mb-2 d-block">Nom Complet *</label>
                            <input type="text" class="form-control form-control-lg fw-bold text-navy @error('nom') is-invalid @enderror" 
                                id="nom" name="nom" value="{{ old('nom') }}" 
                                placeholder="Ex: M. Amadou Diallo" required>
                            @error('nom')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6">
                            <label for="telephone" class="small text-muted fw-bold text-uppercase mb-2 d-block">Numéro de Téléphone *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone"></i></span>
                                <input type="tel" class="form-control fw-bold text-navy border-start-0 @error('telephone') is-invalid @enderror" 
                                    id="telephone" name="telephone" value="{{ old('telephone') }}" 
                                    placeholder="Ex: 6XX XX XX XX" required>
                            </div>
                            @error('telephone')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="small text-muted fw-bold text-uppercase mb-2 d-block">Adresse Email</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control fw-bold text-navy border-start-0 @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email') }}" 
                                    placeholder="client@domaine.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-12">
                            <label for="adresse" class="small text-muted fw-bold text-uppercase mb-2 d-block">Adresse Géographique</label>
                            <textarea class="form-control fw-medium @error('adresse') is-invalid @enderror" 
                                id="adresse" name="adresse" rows="3" 
                                placeholder="Ex: Quartier Akwa, Rue 123...">{{ old('adresse') }}</textarea>
                            @error('adresse')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Enregistrer le Client
                        </button>
                        <a href="{{ route('clients.index') }}" class="btn btn-light rounded-pill px-4 border small fw-bold">
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
                <h6 class="fw-bold text-navy text-uppercase ls-wide small mb-4">Directives</h6>
                
                <div class="alert alert-info border-0 shadow-none bg-white p-3 rounded-4 mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <small>Le numéro de téléphone sert d'identifiant unique pour la fidélité.</small>
                </div>

                <div class="small text-secondary">
                    <p class="mb-3"><strong>Champs obligatoires :</strong></p>
                    <ul class="ps-3 mb-4">
                        <li class="mb-2">Le nom complet pour la facturation.</li>
                        <li>Un numéro de téléphone valide.</li>
                    </ul>
                    
                    <p class="mb-3"><strong>Avantages :</strong></p>
                    <ul class="ps-3 mb-0">
                        <li class="mb-2">Calcul automatique des points.</li>
                        <li>Historique des transactions dédié.</li>
                        <li>Possibilité de crédits autorisés.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
