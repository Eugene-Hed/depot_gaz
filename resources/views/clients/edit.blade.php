@extends('layouts.app')

@section('title', 'Modifier client - ' . $client->nom)

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-pencil"></i> Modifier client</h1>
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
                    <h5 class="mb-0"><i class="bi bi-form-check"></i> Informations du client</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('clients.update', $client) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nom -->
                        <div class="mb-4">
                            <label for="nom" class="form-label fw-bold">
                                <i class="bi bi-person"></i> Nom complet *
                            </label>
                            <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                id="nom" name="nom" value="{{ old('nom', $client->nom) }}" required>
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
                                id="telephone" name="telephone" value="{{ old('telephone', $client->telephone) }}" required>
                            @error('telephone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">
                                <i class="bi bi-envelope"></i> Email (optionnel)
                            </label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $client->email) }}">
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
                                id="adresse" name="adresse" rows="3">{{ old('adresse', $client->adresse) }}</textarea>
                            @error('adresse')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Enregistrer les modifications
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
            <!-- Informations client -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-muted mb-1">Statut</p>
                        <p class="mb-0">
                            @if($client->statut === 'actif')
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Actif</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-x-circle-fill"></i> Inactif</span>
                            @endif
                        </p>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <p class="text-muted mb-1">Points de fidélité</p>
                        <p class="mb-0">
                            <strong class="text-warning" style="font-size: 1.5rem;">
                                <i class="bi bi-star-fill"></i> {{ $client->points_fidelite }}
                            </strong>
                        </p>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <p class="text-muted mb-1">Client depuis</p>
                        <p class="mb-0">
                            <strong>{{ $client->created_at->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">{{ $client->created_at->diffForHumans() }}</small>
                        </p>
                    </div>

                    <hr>

                    <div>
                        <p class="text-muted mb-1">Dernière mise à jour</p>
                        <p class="mb-0">
                            <small class="text-muted">{{ $client->updated_at->format('d/m/Y H:i') }}</small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm bg-light">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-circle"></i> Zone dangereuse</h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">Supprimer ce client de la base de données</p>
                    <button type="button" class="btn btn-danger w-100" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i> Supprimer le client
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation -->
    <form id="deleteForm" action="{{ route('clients.destroy', $client) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete() {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>

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
