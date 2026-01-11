@extends('layouts.app')

@section('title', 'Modifier stock - ' . $stock->typeBouteille->nom)

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-pencil"></i> Ajuster stock</h1>
            <p class="text-muted">
                <strong>{{ $stock->typeBouteille->marque->nom }}</strong> - {{ $stock->typeBouteille->nom }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('stocks.show', $stock) }}" class="btn btn-info">
                <i class="bi bi-clock-history"></i> Historique
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- État actuel -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> État actuel</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Bouteilles pleines</p>
                            <h4 class="text-success mb-0">{{ $stock->quantite_pleine }}</h4>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Bouteilles vides</p>
                            <h4 class="text-warning mb-0">{{ $stock->quantite_vide }}</h4>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Total</p>
                            <h4 class="mb-0">{{ $stock->quantite_pleine + $stock->quantite_vide }}</h4>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Seuil d'alerte</p>
                            <h4 class="mb-0">{{ $stock->typeBouteille->seuil_alerte }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire d'ajustement -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Ajustement du stock</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('stocks.update', $stock) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-lightbulb"></i>
                            <strong>Important:</strong> Cet écran vous permet d'ajuster les quantités directement. 
                            Une entrée "Ajustement" sera automatiquement créée dans l'historique.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="quantite_pleine" class="form-label fw-bold">
                                    <i class="bi bi-check-circle-fill text-success"></i> Bouteilles pleines
                                </label>
                                <input type="number" class="form-control form-control-lg @error('quantite_pleine') is-invalid @enderror" 
                                    id="quantite_pleine" name="quantite_pleine" value="{{ $stock->quantite_pleine }}" required>
                                @error('quantite_pleine')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    Valeur actuelle: <strong>{{ $stock->quantite_pleine }}</strong>
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label for="quantite_vide" class="form-label fw-bold">
                                    <i class="bi bi-exclamation-circle-fill text-warning"></i> Bouteilles vides
                                </label>
                                <input type="number" class="form-control form-control-lg @error('quantite_vide') is-invalid @enderror" 
                                    id="quantite_vide" name="quantite_vide" value="{{ $stock->quantite_vide }}" required>
                                @error('quantite_vide')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    Valeur actuelle: <strong>{{ $stock->quantite_vide }}</strong>
                                </small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="commentaire" class="form-label fw-bold">
                                <i class="bi bi-chat-dots"></i> Commentaire (optionnel)
                            </label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="3" 
                                placeholder="Expliquez la raison de cet ajustement (ex: inventaire du 15/01/2026)">{{ old('commentaire') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Enregistrer l'ajustement
                            </button>
                            <a href="{{ route('stocks.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel d'information -->
        <div class="col-md-4">
            <div class="card shadow-sm bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-2"><strong>Produit</strong></p>
                        <p class="text-muted mb-0">{{ $stock->typeBouteille->nom }}</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="mb-2"><strong>Marque</strong></p>
                        <p class="text-muted mb-0">{{ $stock->typeBouteille->marque->nom }}</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="mb-2"><strong>Taille</strong></p>
                        <p class="text-muted mb-0">{{ $stock->typeBouteille->taille }}L</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="mb-2"><strong>Seuil d'alerte</strong></p>
                        <p class="text-muted mb-0">{{ $stock->typeBouteille->seuil_alerte }} bouteilles</p>
                    </div>

                    <hr>

                    <div>
                        <p class="mb-2"><strong>Taux de remplissage</strong></p>
                        @php
                            $taux = $stock->quantite_pleine > 0 ? ($stock->quantite_pleine / ($stock->quantite_pleine + $stock->quantite_vide)) * 100 : 0;
                        @endphp
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $taux }}%" 
                                aria-valuenow="{{ $taux }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ round($taux) }}%
                            </div>
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
