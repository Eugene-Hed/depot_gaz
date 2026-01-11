@extends('layouts.app')

@section('title', 'Ajouter une Ligne de Commande')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1><i class="bi bi-plus-circle"></i> Ajouter une Ligne de Commande</h1>
        <p class="text-muted">Commande #{{ $commande->numero_commande }} - {{ $commande->fournisseur->nom }}</p>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <h4 class="alert-heading">Erreurs de validation</h4>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('commandes.details.store', $commande) }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type_bouteille_id" class="form-label">Type de Bouteille <span class="text-danger">*</span></label>
                        <select class="form-select @error('type_bouteille_id') is-invalid @enderror" 
                                id="type_bouteille_id" name="type_bouteille_id" required onchange="updatePrix()">
                            <option value="">-- Sélectionner --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @selected(old('type_bouteille_id') == $type->id)
                                        data-prix="{{ $type->prix_vente }}">
                                    {{ $type->nom }} ({{ $type->taille }} - {{ $type->marque->nom }})
                                </option>
                            @endforeach
                        </select>
                        @error('type_bouteille_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="quantite_commandee" class="form-label">Quantité Commandée <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantite_commandee') is-invalid @enderror" 
                               id="quantite_commandee" name="quantite_commandee" min="1" required 
                               value="{{ old('quantite_commandee') }}" onchange="updateMontant()">
                        @error('quantite_commandee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="quantite_livree" class="form-label">Quantité Livrée</label>
                        <input type="number" class="form-control @error('quantite_livree') is-invalid @enderror" 
                               id="quantite_livree" name="quantite_livree" min="0" 
                               value="{{ old('quantite_livree', 0) }}">
                        @error('quantite_livree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="prix_unitaire" class="form-label">Prix Unitaire (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('prix_unitaire') is-invalid @enderror" 
                               id="prix_unitaire" name="prix_unitaire" step="0.01" min="0" required 
                               value="{{ old('prix_unitaire') }}" onchange="updateMontant()">
                        @error('prix_unitaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="statut_ligne" class="form-label">Statut <span class="text-danger">*</span></label>
                <select class="form-select @error('statut_ligne') is-invalid @enderror" 
                        id="statut_ligne" name="statut_ligne" required>
                    <option value="en_attente" @selected(old('statut_ligne') == 'en_attente')>En Attente</option>
                    <option value="partielle" @selected(old('statut_ligne') == 'partielle')>Partielle</option>
                    <option value="livree" @selected(old('statut_ligne') == 'livree')>Livrée</option>
                    <option value="annulee" @selected(old('statut_ligne') == 'annulee')>Annulée</option>
                </select>
                @error('statut_ligne')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info">
                <p class="mb-0"><strong>Montant de la Ligne:</strong> <span id="montant-display">0.00</span> FCFA</p>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Ajouter la Ligne
                </button>
                <a href="{{ route('commandes.details.index', $commande) }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function updatePrix() {
    const select = document.getElementById('type_bouteille_id');
    const selected = select.options[select.selectedIndex];
    const prix = selected.getAttribute('data-prix') || 0;
    document.getElementById('prix_unitaire').value = prix;
    updateMontant();
}

function updateMontant() {
    const quantite = parseFloat(document.getElementById('quantite_commandee').value) || 0;
    const prix = parseFloat(document.getElementById('prix_unitaire').value) || 0;
    const montant = quantite * prix;
    document.getElementById('montant-display').innerText = montant.toFixed(2);
}
</script>
@endsection
