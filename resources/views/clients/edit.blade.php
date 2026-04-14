@extends('layouts.app')

@section('title', 'Modifier Client - ' . $client->nom_complet)

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Mettre à Jour le Client</h1>
        <p class="text-secondary small mb-0">Modification des coordonnées de {{ $client->nom_complet }}</p>
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
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-pencil me-2"></i> Modification du Profil</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('clients.update', $client) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Nom -->
                        <div class="col-md-12">
                            <label for="nom" class="small text-muted fw-bold text-uppercase mb-2 d-block">Nom Complet *</label>
                            <input type="text" class="form-control form-control-lg fw-bold text-navy @error('nom') is-invalid @enderror" 
                                id="nom" name="nom" value="{{ old('nom', $client->nom) }}" required>
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
                                    id="telephone" name="telephone" value="{{ old('telephone', $client->telephone) }}" required>
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
                                    id="email" name="email" value="{{ old('email', $client->email) }}">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-12">
                            <label for="adresse" class="small text-muted fw-bold text-uppercase mb-2 d-block">Adresse Géographique</label>
                            <textarea class="form-control fw-medium @error('adresse') is-invalid @enderror" 
                                id="adresse" name="adresse" rows="3">{{ old('adresse', $client->adresse) }}</textarea>
                            @error('adresse')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Sauvegarder les Modifications
                        </button>
                        <button type="button" class="btn btn-light rounded-pill px-4 border text-danger small fw-bold ms-auto" onclick="confirmDelete()">
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
                <h6 class="fw-bold text-navy text-uppercase ls-wide small mb-4">Statistiques Client</h6>
                
                <div class="mb-4">
                    <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Fidélité Accumulée</span>
                    <h3 class="fw-bold text-warning mb-0"><i class="bi bi-star-fill me-2"></i>{{ $client->points_fidelite }} <small class="text-muted font-2xs">points</small></h3>
                </div>

                <div class="small text-secondary">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Membre depuis</span>
                        <span class="fw-bold text-navy">{{ $client->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Dernière activité</span>
                        <span class="fw-bold text-navy">{{ $client->updated_at->diffForHumans() }}</span>
                    </div>
                </div>

                <hr class="opacity-10 my-4">

                <div class="alert alert-info border-0 shadow-none bg-white p-3 rounded-4 mb-0">
                    <i class="bi bi-shield-lock me-2"></i>
                    <small>Les modifications de numéro de téléphone impacteront la recherche de ce client par SMS ou outils externes.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="deleteForm" action="{{ route('clients.destroy', $client) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete() {
        if (confirm('Supprimer définitivement ce client ? Cette action est irréversible.')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection
