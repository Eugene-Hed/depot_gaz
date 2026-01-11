@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-edit"></i> Modifier fournisseur</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 offset-lg-2">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('fournisseurs.update', $fournisseur) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="fas fa-building"></i> Nom du fournisseur
                            </label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Ex: Butagaz Sénégal, Primagaz..." 
                                   value="{{ old('nom', $fournisseur->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone"></i> Téléphone
                                </label>
                                <input type="tel" class="form-control @error('telephone') is-invalid @enderror" 
                                       id="telephone" name="telephone" placeholder="+221 77 123 45 67" 
                                       value="{{ old('telephone', $fournisseur->telephone) }}" required>
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" placeholder="contact@fournisseur.com" 
                                       value="{{ old('email', $fournisseur->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="adresse" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Adresse
                            </label>
                            <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                      id="adresse" name="adresse" rows="3" 
                                      placeholder="123 Rue de la Paix, Dakar">{{ old('adresse', $fournisseur->adresse) }}</textarea>
                            @error('adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="statut" class="form-label">
                                <i class="fas fa-toggle-on"></i> Statut
                            </label>
                            <select class="form-select @error('statut') is-invalid @enderror" 
                                    id="statut" name="statut" required>
                                <option value="actif" {{ old('statut', $fournisseur->statut) === 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('statut', $fournisseur->statut) === 'inactif' ? 'selected' : '' }}>Inactif</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @error('error')
                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                            <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
