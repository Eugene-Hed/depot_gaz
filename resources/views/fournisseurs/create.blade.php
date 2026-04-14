@extends('layouts.app')

@section('title', 'Nouveau Fournisseur')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Enregistrer un Fournisseur</h1>
        <p class="text-secondary small mb-0">Ajout d'un nouveau partenaire à la chaîne logistique</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('fournisseurs.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-corporate border-0 shadow-sm">
            <div class="card-header bg-navy text-white p-4">
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-truck me-2"></i> Profil Fournisseur</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('fournisseurs.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <!-- Nom -->
                        <div class="col-md-12">
                            <label for="nom" class="small text-muted fw-bold text-uppercase mb-2 d-block">Nom de l'Entité *</label>
                            <input type="text" class="form-control form-control-lg fw-bold text-navy @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Ex: Gaz-Pro Distribution" 
                                   value="{{ old('nom') }}" required>
                            @error('nom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6">
                            <label for="telephone" class="small text-muted fw-bold text-uppercase mb-2 d-block">Contact Principal *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone"></i></span>
                                <input type="tel" class="form-control fw-bold text-navy border-start-0 @error('telephone') is-invalid @enderror" 
                                       id="telephone" name="telephone" placeholder="+221 ..." 
                                       value="{{ old('telephone') }}" required>
                            </div>
                            @error('telephone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="small text-muted fw-bold text-uppercase mb-2 d-block">Email de Liaison</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control fw-bold text-navy border-start-0 @error('email') is-invalid @enderror" 
                                       id="email" name="email" placeholder="contact@fournisseur.com" 
                                       value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-12">
                            <label for="adresse" class="small text-muted fw-bold text-uppercase mb-2 d-block">Siège Social / Adresse</label>
                            <textarea class="form-control fw-medium @error('adresse') is-invalid @enderror" 
                                      id="adresse" name="adresse" rows="3" 
                                      placeholder="Ex: Zone Industrielle, Lot 45...">{{ old('adresse') }}</textarea>
                            @error('adresse')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div class="col-md-12">
                            <label class="small text-muted fw-bold text-uppercase mb-2 d-block">Statut Opérationnel</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="statut" id="statut_actif" 
                                           value="actif" {{ old('statut') === 'actif' || !old('statut') ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-navy w-100 py-2 rounded-pill fw-bold" for="statut_actif">
                                        <i class="bi bi-check-circle me-1"></i> Partenaire Actif
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="statut" id="statut_inactif" 
                                           value="inactif" {{ old('statut') === 'inactif' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-navy w-100 py-2 rounded-pill fw-bold" for="statut_inactif">
                                        <i class="bi bi-pause-circle me-1"></i> En Suspens
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Créer le Fournisseur
                        </button>
                        <a href="{{ route('fournisseurs.index') }}" class="btn btn-light rounded-pill px-4 border small fw-bold">
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
                <h6 class="fw-bold text-navy text-uppercase ls-wide small mb-4">Directives Logistiques</h6>
                
                <div class="alert alert-info border-0 shadow-none bg-white p-3 rounded-4 mb-4">
                    <i class="bi bi-shield-check me-2"></i>
                    <small>Le nom du fournisseur doit être unique pour éviter les doublons dans les registres d'achats.</small>
                </div>

                <div class="small text-secondary">
                    <p class="mb-3"><strong>Impact du statut :</strong></p>
                    <ul class="ps-3 mb-0">
                        <li class="mb-3"><strong>Actif :</strong> Le fournisseur apparaîtra dans les listes de sélection pour les bons de commande ou les réapprovisionnements.</li>
                        <li><strong>En Suspens :</strong> Le fournisseur est archivé. Ses données historiques restent consultables mais il ne peut plus faire l'objet de nouvelles opérations.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
