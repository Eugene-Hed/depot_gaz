@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1 class="d-flex align-items-center gap-3">
                <i class="bi bi-pencil-square" style="font-size: 2rem; color: #6366f1;"></i>
                <span>Modifier le fournisseur</span>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('fournisseurs.update', $fournisseur) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="nom" class="form-label fw-bold">
                                <i class="bi bi-building"></i> Nom du fournisseur
                            </label>
                            <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Ex: Butagaz Sénégal, Primagaz..." 
                                   value="{{ old('nom', $fournisseur->nom) }}" required>
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
                                       value="{{ old('telephone', $fournisseur->telephone) }}" required>
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
                                       value="{{ old('email', $fournisseur->email) }}">
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
                                      placeholder="123 Rue de la Paix, Dakar">{{ old('adresse', $fournisseur->adresse) }}</textarea>
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
                                       value="actif" {{ old('statut', $fournisseur->statut) === 'actif' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-success" for="statut_actif">
                                    <i class="bi bi-check-circle"></i> Actif
                                </label>

                                <input type="radio" class="btn-check" name="statut" id="statut_inactif" 
                                       value="inactif" {{ old('statut', $fournisseur->statut) === 'inactif' ? 'checked' : '' }}>
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
                                <i class="bi bi-check-circle"></i> Mettre à jour
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
            <!-- Info Card -->
            <div class="card border-0 shadow-sm mb-3 bg-light">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-info-circle"></i> Informations
                    </h5>
                    <div class="mb-3">
                        <small class="text-muted d-block">Code du fournisseur</small>
                        <strong class="badge bg-dark">{{ $fournisseur->code_fournisseur }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Statut actuel</small>
                        @if ($fournisseur->statut === 'actif')
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Actif</span>
                        @else
                            <span class="badge bg-secondary"><i class="bi bi-pause-circle"></i> Inactif</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Créé le</small>
                        <small class="text-dark">{{ $fournisseur->created_at->format('d/m/Y à H:i') }}</small>
                    </div>
                    <div>
                        <small class="text-muted d-block">Dernière modification</small>
                        <small class="text-dark">{{ $fournisseur->updated_at->format('d/m/Y à H:i') }}</small>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card border-danger border-2 shadow-sm">
                <div class="card-body border-bottom-danger">
                    <h5 class="card-title fw-bold text-danger mb-3">
                        <i class="bi bi-exclamation-triangle"></i> Zone de danger
                    </h5>
                    <p class="text-muted small mb-3">
                        Cette action est irréversible. Le fournisseur et toutes ses données seront supprimés.
                    </p>
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Supprimer ce fournisseur
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger-subtle border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle"></i> Confirmation de suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Êtes-vous <strong>absolument sûr</strong> de vouloir supprimer le fournisseur ?</p>
                <div class="alert alert-warning" role="alert">
                    <strong>{{ $fournisseur->nom }}</strong>
                </div>
                <p class="text-muted small mb-0">Cette action est définitive et ne peut pas être annulée.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Annuler
                </button>
                <form id="deleteForm" action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
