@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-plus-circle"></i> Nouvelle marque</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('marques.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Form Column (Left - 8 cols) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0"><i class="bi bi-form-check"></i> Informations de la marque</h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle-fill"></i> <strong>Erreur!</strong> Veuillez corriger les erreurs ci-dessous.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('marques.store') }}" method="POST">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-4">
                            <label for="nom" class="form-label fw-bold">
                                <i class="bi bi-tag"></i> Nom de la marque *
                            </label>
                            <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Ex: TotalEnergies, Butagaz, Primagaz..." 
                                   value="{{ old('nom') }}" required autofocus>
                            @error('nom')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">Nom unique de la marque</small>
                        </div>

                        <!-- Statut -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">
                                <i class="bi bi-toggle-on"></i> Statut *
                            </label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="statut" id="statut_actif" 
                                           value="actif" {{ old('statut', 'actif') === 'actif' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="statut_actif">
                                        <span class="badge bg-success">Actif</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="statut" id="statut_inactif" 
                                           value="inactif" {{ old('statut') === 'inactif' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="statut_inactif">
                                        <span class="badge bg-secondary">Inactif</span>
                                    </label>
                                </div>
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

                        <!-- Boutons -->
                        <div class="d-flex gap-2 pt-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg"></i> Créer la marque
                            </button>
                            <a href="{{ route('marques.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-lg"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar (Right - 4 cols) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-header bg-info text-white border-0">
                    <h5 class="card-title mb-0"><i class="bi bi-info-circle"></i> Guide</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="mb-2"><strong>Nom de la marque</strong></p>
                        <p class="small text-muted">
                            Nom unique et identifiant de la marque (ex: TotalEnergies, Butagaz)
                        </p>
                        <div class="alert alert-light small mt-2">
                            Ce nom ne peut pas être modifié après création
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <p class="mb-2"><strong>Statut</strong></p>
                        <p class="small text-muted">
                            Indique si la marque est disponible pour les opérations
                        </p>
                    </div>

                    <hr>

                    <div class="alert alert-info small">
                        <i class="bi bi-lightbulb"></i>
                        <strong>Conseil:</strong> Assurez-vous que le nom est correct avant de créer la marque.
                    </div>
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
