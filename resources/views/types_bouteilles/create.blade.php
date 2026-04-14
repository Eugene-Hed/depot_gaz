@extends('layouts.app')

@section('title', 'Nouveau Produit')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Configurer un Produit</h1>
        <p class="text-secondary small mb-0">Paramétrage technique et tarification des types de bouteilles</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('types-bouteilles.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Retour au catalogue
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-corporate border-0 shadow-sm">
            <div class="card-header bg-navy text-white p-4">
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-box-seam me-2"></i> Spécifications du Produit</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('types-bouteilles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Marque et Taille -->
                        <div class="col-md-6">
                            <label for="id_marque" class="small text-muted fw-bold text-uppercase mb-2 d-block">Marque Associée *</label>
                            <select class="form-select @error('id_marque') is-invalid @enderror" id="id_marque" name="id_marque" required>
                                <option value="">-- Sélectionner --</option>
                                @foreach ($marques as $marque)
                                    <option value="{{ $marque->id }}" {{ old('id_marque') == $marque->id ? 'selected' : '' }}>{{ $marque->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="taille" class="small text-muted fw-bold text-uppercase mb-2 d-block">Dénomination (Litres/Kg) *</label>
                            <input type="text" class="form-control fw-bold text-navy @error('taille') is-invalid @enderror" 
                                   id="taille" name="taille" placeholder="Ex: 12.5kg, 50L..." 
                                   value="{{ old('taille') }}" required>
                        </div>

                        <!-- Image -->
                        <div class="col-md-12">
                            <label for="image" class="small text-muted fw-bold text-uppercase mb-2 d-block">Visuel de la Bouteille</label>
                            <div class="p-3 border rounded-4 bg-light">
                                <input type="file" class="form-control form-control-sm @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                <div class="mt-2 text-center">
                                    <img id="preview" src="" alt="Aperçu" class="rounded shadow-sm bg-white p-2" style="display: none; max-width: 150px; max-height: 150px; margin: 0 auto;">
                                </div>
                            </div>
                        </div>

                        <!-- Seuil d'alerte -->
                        <div class="col-md-6">
                            <label for="seuil_alerte" class="small text-muted fw-bold text-uppercase mb-2 d-block">Seuil de Réapprovisionnement *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-bell text-warning"></i></span>
                                <input type="number" class="form-control fw-bold @error('seuil_alerte') is-invalid @enderror" 
                                       id="seuil_alerte" name="seuil_alerte" value="{{ old('seuil_alerte', 10) }}" min="0" required>
                            </div>
                            <small class="font-2xs text-secondary italic">Alerte auto quand le stock descend sous ce nombre.</small>
                        </div>

                        <div class="col-12"><hr class="opacity-10"></div>

                        <!-- Prix -->
                        <div class="col-md-6">
                            <label for="prix_consigne" class="small text-muted fw-bold text-uppercase mb-2 d-block">Valeur Consigne (Fer) *</label>
                            <div class="input-group">
                                <input type="number" class="form-control fw-bold text-navy @error('prix_consigne') is-invalid @enderror" 
                                       id="prix_consigne" name="prix_consigne" placeholder="0" step="1"
                                       value="{{ old('prix_consigne') }}" required oninput="calculerPrixPleine()">
                                <span class="input-group-text bg-light fw-bold font-2xs">FCFA</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="prix_recharge" class="small text-muted fw-bold text-uppercase mb-2 d-block">Prix Recharge (Gaz) *</label>
                            <div class="input-group">
                                <input type="number" class="form-control fw-bold text-navy @error('prix_recharge') is-invalid @enderror" 
                                       id="prix_recharge" name="prix_recharge" placeholder="0" step="1"
                                       value="{{ old('prix_recharge') }}" required oninput="calculerPrixPleine()">
                                <span class="input-group-text bg-light fw-bold font-2xs">FCFA</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="p-3 rounded-4 bg-navy text-white d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold small text-uppercase opacity-75">Prix de Vente Unitaire (Pleine)</h6>
                                    <small class="font-2xs italic opacity-50">Calcul automatique : Consigne + Recharge</small>
                                </div>
                                <h3 class="mb-0 fw-extrabold" id="prix_pleine_display">0 F</h3>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="col-md-12">
                            <label class="small text-muted fw-bold text-uppercase mb-2 d-block">Visibilité Catalogue</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="statut" id="statut_actif" 
                                       value="actif" {{ old('statut', 'actif') === 'actif' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-navy py-2" for="statut_actif">En Vente</label>

                                <input type="radio" class="btn-check" name="statut" id="statut_inactif" 
                                       value="inactif" {{ old('statut') === 'inactif' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-navy py-2" for="statut_inactif">Retiré / Caché</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Valider la Fiche Produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-corporate border-0 shadow-sm bg-light h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-navy text-uppercase ls-wide small mb-4">Aide au paramétrage</h6>
                <div class="small text-secondary">
                    <div class="mb-4">
                        <p class="fw-bold mb-2">Structure des prix :</p>
                        <p class="mb-3">Le système gère deux composantes distinctes pour permettre les échanges de types différents.</p>
                        <ul class="ps-3">
                            <li class="mb-3"><strong>Consigne :</strong> Représente la valeur physique de la bouteille métallique. Elle est remboursable ou échangeable.</li>
                            <li><strong>Recharge :</strong> Représente uniquement la valeur du gaz contenu.</li>
                        </ul>
                    </div>
                    
                    <div class="bg-white p-3 rounded-4 border border-navy border-opacity-10 mb-4">
                        <i class="bi bi-shield-lock text-primary me-2"></i>
                        <span class="font-2xs fw-bold text-navy">NOTE :</span>
                        <p class="font-2xs mb-0 mt-1">Les prix modifiés ici impacteront uniquement les futures transactions. L'historique passé reste inchangé.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function calculerPrixPleine() {
        const consigne = parseFloat(document.getElementById('prix_consigne').value) || 0;
        const recharge = parseFloat(document.getElementById('prix_recharge').value) || 0;
        const total = consigne + recharge;
        document.getElementById('prix_pleine_display').textContent = total.toLocaleString() + ' F';
    }

    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', calculerPrixPleine);
</script>

<style>
    .fw-extrabold { font-weight: 800; }
</style>
@endsection
