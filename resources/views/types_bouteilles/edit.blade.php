@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h1><i class="fas fa-edit"></i> Modifier type de bouteille</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('types-bouteilles.update', $typeBouteille) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">
                                    <i class="fas fa-tag"></i> Nom du type
                                </label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                       id="nom" name="nom" placeholder="Ex: Bouteille standard, Premium..." 
                                       value="{{ old('nom', $typeBouteille->nom) }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="taille" class="form-label">
                                    <i class="fas fa-expand"></i> Taille
                                </label>
                                <input type="text" class="form-control @error('taille') is-invalid @enderror" 
                                       id="taille" name="taille" placeholder="Ex: 12kg, 5kg, 2.5kg..." 
                                       value="{{ old('taille', $typeBouteille->taille) }}" required>
                                @error('taille')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_marque" class="form-label">
                                    <i class="fas fa-cube"></i> Marque
                                </label>
                                <select class="form-select @error('id_marque') is-invalid @enderror" 
                                        id="id_marque" name="id_marque" required>
                                    <option value="">-- Sélectionner une marque --</option>
                                    @foreach ($marques as $marque)
                                        <option value="{{ $marque->id }}" 
                                                {{ old('id_marque', $typeBouteille->marque_id) == $marque->id ? 'selected' : '' }}>
                                            {{ $marque->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_marque')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="seuil_alerte" class="form-label">
                                    <i class="fas fa-exclamation-triangle"></i> Seuil d'alerte (qty)
                                </label>
                                <input type="number" class="form-control @error('seuil_alerte') is-invalid @enderror" 
                                       id="seuil_alerte" name="seuil_alerte" 
                                       value="{{ old('seuil_alerte', $typeBouteille->seuil_alerte) }}" min="0" required>
                                @error('seuil_alerte')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="prix_vente" class="form-label">
                                    <i class="fas fa-tag"></i> Prix vente (FCFA)
                                </label>
                                <input type="number" class="form-control @error('prix_vente') is-invalid @enderror" 
                                       id="prix_vente" name="prix_vente" placeholder="0" step="100"
                                       value="{{ old('prix_vente', $typeBouteille->prix_vente) }}" required>
                                @error('prix_vente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="prix_consigne" class="form-label">
                                    <i class="fas fa-coins"></i> Prix consigne (FCFA)
                                </label>
                                <input type="number" class="form-control @error('prix_consigne') is-invalid @enderror" 
                                       id="prix_consigne" name="prix_consigne" placeholder="0" step="100"
                                       value="{{ old('prix_consigne', $typeBouteille->prix_consigne) }}" required>
                                @error('prix_consigne')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="prix_recharge" class="form-label">
                                    <i class="fas fa-charging-station"></i> Prix recharge (FCFA)
                                </label>
                                <input type="number" class="form-control @error('prix_recharge') is-invalid @enderror" 
                                       id="prix_recharge" name="prix_recharge" placeholder="0" step="100"
                                       value="{{ old('prix_recharge', $typeBouteille->prix_recharge) }}" required>
                                @error('prix_recharge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="statut" class="form-label">
                                <i class="fas fa-toggle-on"></i> Statut
                            </label>
                            <select class="form-select @error('statut') is-invalid @enderror" 
                                    id="statut" name="statut" required>
                                <option value="actif" {{ old('statut', $typeBouteille->statut) === 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('statut', $typeBouteille->statut) === 'inactif' ? 'selected' : '' }}>Inactif</option>
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
                            <a href="{{ route('types-bouteilles.index') }}" class="btn btn-secondary">
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
