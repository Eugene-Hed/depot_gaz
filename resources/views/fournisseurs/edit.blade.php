@extends('layouts.app')

@section('title', 'Modifier Fournisseur - ' . $fournisseur->nom)

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Profil Fournisseur</h1>
        <p class="text-secondary small mb-0">Modification des informations de {{ $fournisseur->nom }}</p>
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
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-pencil-square me-2"></i> Modification des Coordonnées</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('fournisseurs.update', $fournisseur) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Nom -->
                        <div class="col-md-12">
                            <label for="nom" class="small text-muted fw-bold text-uppercase mb-2 d-block">Nom de l'Entité *</label>
                            <input type="text" class="form-control form-control-lg fw-bold text-navy @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom', $fournisseur->nom) }}" required>
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
                                       id="telephone" name="telephone" value="{{ old('telephone', $fournisseur->telephone) }}" required>
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
                                       id="email" name="email" value="{{ old('email', $fournisseur->email) }}">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-12">
                            <label for="adresse" class="small text-muted fw-bold text-uppercase mb-2 d-block">Siège Social / Adresse</label>
                            <textarea class="form-control fw-medium @error('adresse') is-invalid @enderror" 
                                      id="adresse" name="adresse" rows="3">{{ old('adresse', $fournisseur->adresse) }}</textarea>
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
                                           value="actif" {{ old('statut', $fournisseur->statut) === 'actif' ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-navy w-100 py-2 rounded-pill fw-bold" for="statut_actif">
                                        <i class="bi bi-check-circle me-1"></i> Partenaire Actif
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="statut" id="statut_inactif" 
                                           value="inactif" {{ old('statut', $fournisseur->statut) === 'inactif' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-navy w-100 py-2 rounded-pill fw-bold" for="statut_inactif">
                                        <i class="bi bi-pause-circle me-1"></i> En Suspens
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Mettre à Jour le Profil
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
                        <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Identifiant Unique</span>
                        <h6 class="fw-bold text-navy mb-0 small">CODE: {{ $fournisseur->code_fournisseur }}</h6>
                    </div>

                    <div class="mb-4">
                        <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Historique Système</span>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Créé le</span>
                            <span class="fw-medium text-navy">{{ $fournisseur->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Dernier Edit</span>
                            <span class="fw-medium text-navy">{{ $fournisseur->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded-4 border border-navy border-opacity-10">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        <span class="font-2xs fw-bold text-navy">NOTE :</span>
                        <p class="font-2xs mb-0 mt-1">Les modifications de nom peuvent impacter la lisibilité des anciens rapports d'approvisionnement.</p>
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
                <p class="mb-3 text-navy fw-bold">Suppression définitive de l'entité :</p>
                <div class="bg-light p-3 rounded-4 mb-4 border">
                    <h6 class="mb-1 fw-bold text-navy">{{ $fournisseur->nom }}</h6>
                    <small class="text-secondary">Code: {{ $fournisseur->code_fournisseur }}</small>
                </div>
                <p class="text-muted small mb-0">Cette action est irréversible. Toutes les données associées à ce fournisseur seront archivées ou supprimées selon les contraintes d'intégrité.</p>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold font-2xs text-uppercase" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold font-2xs text-uppercase">Confirmer la suppression</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
