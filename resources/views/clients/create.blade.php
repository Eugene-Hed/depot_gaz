@extends('layouts.app')

@section('title', 'Créer un client')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-person-plus"></i> Nouveau client</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-form-check"></i> Formulaire du client</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-4">
                            <label for="nom" class="form-label fw-bold">
                                <i class="bi bi-person"></i> Nom complet *
                            </label>
                            <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                id="nom" name="nom" value="{{ old('nom') }}" 
                                placeholder="ex: Jean Dupont" required>
                            @error('nom')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div class="mb-4">
                            <label for="telephone" class="form-label fw-bold">
                                <i class="bi bi-telephone"></i> Téléphone *
                            </label>
                            <input type="tel" class="form-control form-control-lg @error('telephone') is-invalid @enderror" 
                                id="telephone" name="telephone" value="{{ old('telephone') }}" 
                                placeholder="ex: +221 77 123 45 67" required>
                            @error('telephone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted d-block mt-2">Le numéro de téléphone doit être unique</small>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">
                                <i class="bi bi-envelope"></i> Email (optionnel)
                            </label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" 
                                placeholder="ex: jean.dupont@email.com">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="mb-4">
                            <label for="adresse" class="form-label fw-bold">
                                <i class="bi bi-geo-alt"></i> Adresse (optionnel)
                            </label>
                            <textarea class="form-control form-control-lg @error('adresse') is-invalid @enderror" 
                                id="adresse" name="adresse" rows="3" 
                                placeholder="ex: 123 Rue de la Paix, Dakar">{{ old('adresse') }}</textarea>
                            @error('adresse')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> Créer le client
                            </button>
                            <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-lg">
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
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="mb-2"><strong>Champs obligatoires :</strong></p>
                        <ul class="small text-muted mb-0">
                            <li>Nom complet</li>
                            <li>Téléphone (unique)</li>
                        </ul>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <p class="mb-2"><strong>Champs optionnels :</strong></p>
                        <ul class="small text-muted mb-0">
                            <li>Email</li>
                            <li>Adresse</li>
                        </ul>
                    </div>

                    <hr>

                    <div class="alert alert-info">
                        <i class="bi bi-lightbulb"></i>
                        <strong>Conseil :</strong> Assurez-vous que le numéro de téléphone est correct, car il servira d'identifiant unique du client.
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
