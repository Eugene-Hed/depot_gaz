@extends('layouts.app')

@section('title', 'Créer un stock')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-4"><i class="bi bi-plus-circle"></i> Nouveau mouvement de stock</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Alerte information -->
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle"></i>
                <strong>Types de mouvements:</strong> 
                <ul class="mb-0 mt-2">
                    <li><strong>Entrée</strong> - Augmente les stocks (achat, retour fournisseur)</li>
                    <li><strong>Sortie</strong> - Diminue les stocks (vente, destruction)</li>
                    <li><strong>Ajustement</strong> - Correction manuelle suite à inventaire</li>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('stocks.store') }}" method="POST">
                        @csrf

                        <!-- Type de bouteille -->
                        <div class="mb-4">
                            <label for="id_type_bouteille" class="form-label fw-bold">
                                <i class="bi bi-box2"></i> Type de bouteille
                            </label>
                            <select class="form-select form-select-lg @error('id_type_bouteille') is-invalid @enderror" 
                                id="id_type_bouteille" name="id_type_bouteille" required onchange="updateStockInfo()">
                                <option value="">-- Sélectionner une bouteille --</option>
                                @foreach($typesBouteilles as $type)
                                    <option value="{{ $type->id }}" 
                                        data-seuil="{{ $type->seuil_alerte }}"
                                        data-taille="{{ $type->taille }}"
                                        {{ old('id_type_bouteille') == $type->id ? 'selected' : '' }}>
                                        {{ $type->marque->nom }} - {{ $type->nom }} ({{ $type->taille }}L)
                                    </option>
                                @endforeach
                            </select>
                            @error('id_type_bouteille')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                Seuil d'alerte: <span id="seuil-info" class="badge bg-warning">-</span>
                            </small>
                        </div>

                        <!-- Type de mouvement -->
                        <div class="mb-4">
                            <label for="type_mouvement" class="form-label fw-bold">
                                <i class="bi bi-arrow-left-right"></i> Type de mouvement
                            </label>
                            <div class="btn-group d-flex" role="group" style="gap: 0.5rem;">
                                <input type="radio" class="btn-check" name="type_mouvement" id="mouvement_entree" 
                                    value="entree" {{ old('type_mouvement') == 'entree' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="mouvement_entree">
                                    <i class="bi bi-arrow-down"></i> Entrée
                                </label>

                                <input type="radio" class="btn-check" name="type_mouvement" id="mouvement_sortie" 
                                    value="sortie" {{ old('type_mouvement') == 'sortie' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="mouvement_sortie">
                                    <i class="bi bi-arrow-up"></i> Sortie
                                </label>

                                <input type="radio" class="btn-check" name="type_mouvement" id="mouvement_ajustement" 
                                    value="ajustement" {{ old('type_mouvement') == 'ajustement' ? 'checked' : '' }}>
                                <label class="btn btn-outline-info" for="mouvement_ajustement">
                                    <i class="bi bi-arrow-repeat"></i> Ajustement
                                </label>
                            </div>
                            @error('type_mouvement')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Quantités -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="quantite_pleine" class="form-label fw-bold">
                                    <i class="bi bi-check-circle-fill text-success"></i> Bouteilles pleines
                                </label>
                                <input type="number" class="form-control form-control-lg @error('quantite_pleine') is-invalid @enderror" 
                                    id="quantite_pleine" name="quantite_pleine" value="{{ old('quantite_pleine', 0) }}" min="0" required>
                                @error('quantite_pleine')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="quantite_vide" class="form-label fw-bold">
                                    <i class="bi bi-exclamation-circle-fill text-warning"></i> Bouteilles vides
                                </label>
                                <input type="number" class="form-control form-control-lg @error('quantite_vide') is-invalid @enderror" 
                                    id="quantite_vide" name="quantite_vide" value="{{ old('quantite_vide', 0) }}" min="0" required>
                                @error('quantite_vide')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Motif -->
                        <div class="mb-4">
                            <label for="motif" class="form-label fw-bold">
                                <i class="bi bi-tag"></i> Motif (optionnel)
                            </label>
                            <input type="text" class="form-control" id="motif" name="motif" 
                                value="{{ old('motif') }}" placeholder="vente, casse, retour fournisseur, inventaire, etc.">
                            <small class="text-muted d-block mt-2">
                                Spécifiez la raison du mouvement pour mieux tracer l'historique
                            </small>
                        </div>

                        <!-- Commentaire -->
                        <div class="mb-4">
                            <label for="commentaire" class="form-label fw-bold">
                                <i class="bi bi-chat-dots"></i> Commentaire (optionnel)
                            </label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="3" 
                                placeholder="Détails supplémentaires...">{{ old('commentaire') }}</textarea>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Enregistrer mouvement
                            </button>
                            <a href="{{ route('stocks.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel information -->
        <div class="col-md-4">
            <div class="card shadow-sm bg-light">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Aide</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Guide des mouvements</h6>
                    
                    <div class="mb-3">
                        <p class="mb-2">
                            <strong class="text-success">Entrée</strong>
                        </p>
                        <p class="small text-muted">
                            Utilisez ce mouvement pour enregistrer les réceptions de bouteilles:
                            <ul class="small">
                                <li>Achat auprès des fournisseurs</li>
                                <li>Retour de bouteilles vides par les clients</li>
                                <li>Récupération après nettoyage</li>
                            </ul>
                        </p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="mb-2">
                            <strong class="text-danger">Sortie</strong>
                        </p>
                        <p class="small text-muted">
                            Utilisez ce mouvement pour enregistrer les sorties de bouteilles:
                            <ul class="small">
                                <li>Vente à des clients</li>
                                <li>Destruction de bouteilles cassées</li>
                                <li>Prêt à des tiers</li>
                            </ul>
                        </p>
                    </div>

                    <hr>

                    <div>
                        <p class="mb-2">
                            <strong class="text-info">Ajustement</strong>
                        </p>
                        <p class="small text-muted">
                            Utilisez ce mouvement pour corriger les stocks après inventaire
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateStockInfo() {
            const select = document.getElementById('id_type_bouteille');
            const option = select.options[select.selectedIndex];
            const seuil = option.dataset.seuil || '-';
            document.getElementById('seuil-info').textContent = seuil;
        }
        
        // Initialiser au chargement
        updateStockInfo();
    </script>

    <style>
        .btn-check:checked + .btn {
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.15);
        }
        
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endsection
