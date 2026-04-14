@extends('layouts.app')

@section('title', 'Modifier Produit - ' . $typeBouteille->nom)

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Mise à Jour Fiche Produit</h1>
        <p class="text-secondary small mb-0">Modification technique de {{ $typeBouteille->marque->nom }} {{ $typeBouteille->taille }}</p>
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
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-pencil-square me-2"></i> Modification des Spécifications</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('types-bouteilles.update', $typeBouteille) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Marque et Taille -->
                        <div class="col-md-6">
                            <label for="id_marque" class="small text-muted fw-bold text-uppercase mb-2 d-block">Marque Associée *</label>
                            <select class="form-select @error('id_marque') is-invalid @enderror" id="id_marque" name="id_marque" required>
                                @foreach ($marques as $marque)
                                    <option value="{{ $marque->id }}" {{ old('id_marque', $typeBouteille->id_marque) == $marque->id ? 'selected' : '' }}>{{ $marque->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="taille" class="small text-muted fw-bold text-uppercase mb-2 d-block">Dénomination (Litres/Kg) *</label>
                            <input type="text" class="form-control fw-bold text-navy @error('taille') is-invalid @enderror" 
                                   id="taille" name="taille" value="{{ old('taille', $typeBouteille->taille) }}" required>
                        </div>

                        <!-- Image -->
                        <div class="col-md-12">
                            <label for="image" class="small text-muted fw-bold text-uppercase mb-2 d-block">Visuel de la Bouteille</label>
                            <div class="p-3 border rounded-4 bg-light">
                                <div class="d-flex align-items-center mb-3">
                                    @if($typeBouteille->image)
                                        <img src="{{ $typeBouteille->image_url }}" alt="" class="rounded shadow-sm border p-1 bg-white me-3" style="max-height: 80px;">
                                    @endif
                                    <input type="file" class="form-control form-control-sm @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                </div>
                                <div class="text-center">
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
                                       id="seuil_alerte" name="seuil_alerte" value="{{ old('seuil_alerte', $typeBouteille->seuil_alerte) }}" min="0" required>
                            </div>
                        </div>

                        <div class="col-12"><hr class="opacity-10"></div>

                        <!-- Prix -->
                        <div class="col-md-6">
                            <label for="prix_consigne" class="small text-muted fw-bold text-uppercase mb-2 d-block">Valeur Consigne (Fer) *</label>
                            <div class="input-group">
                                <input type="number" class="form-control fw-bold text-navy @error('prix_consigne') is-invalid @enderror" 
                                       id="prix_consigne" name="prix_consigne" step="1"
                                       value="{{ old('prix_consigne', $typeBouteille->prix_consigne) }}" required oninput="calculerPrixPleine()">
                                <span class="input-group-text bg-light fw-bold font-2xs">FCFA</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="prix_recharge" class="small text-muted fw-bold text-uppercase mb-2 d-block">Prix Recharge (Gaz) *</label>
                            <div class="input-group">
                                <input type="number" class="form-control fw-bold text-navy @error('prix_recharge') is-invalid @enderror" 
                                       id="prix_recharge" name="prix_recharge" step="1"
                                       value="{{ old('prix_recharge', $typeBouteille->prix_recharge) }}" required oninput="calculerPrixPleine()">
                                <span class="input-group-text bg-light fw-bold font-2xs">FCFA</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="p-3 rounded-4 bg-navy text-white d-flex justify-content-between align-items-center shadow-sm">
                                <div>
                                    <h6 class="mb-0 fw-bold small text-uppercase opacity-75">Nouveau Prix de Vente Unitaire</h6>
                                    <small class="font-2xs italic opacity-50">Calcul en temps réel</small>
                                </div>
                                <h3 class="mb-0 fw-extrabold" id="prix_pleine_display">0 F</h3>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="col-md-12">
                            <label class="small text-muted fw-bold text-uppercase mb-2 d-block">Visibilité Catalogue</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="statut" id="statut_actif" 
                                       value="actif" {{ old('statut', $typeBouteille->statut) === 'actif' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-navy py-2" for="statut_actif">En Vente (Actif)</label>

                                <input type="radio" class="btn-check" name="statut" id="statut_inactif" 
                                       value="inactif" {{ old('statut', $typeBouteille->statut) === 'inactif' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-navy py-2" for="statut_inactif">Retiré / Caché</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Enregistrer les Modifications
                        </button>
                        <button type="button" class="btn btn-light rounded-pill px-4 border text-danger small fw-bold ms-auto" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-corporate border-0 shadow-sm bg-light h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-navy text-uppercase ls-wide small mb-4">Résumé des Stocks</h6>
                <div class="small text-navy">
                    <div class="mb-4">
                        <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">État Actuel</span>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Quantité Pleine</span>
                            <span class="fw-bold">{{ $typeBouteille->stock->quantite_pleine ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Quantité Vide</span>
                            <span class="fw-bold">{{ $typeBouteille->stock->quantite_vide ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <hr class="opacity-10 my-4">
                    
                    <div class="bg-white p-3 rounded-4 border border-navy border-opacity-10">
                        <i class="bi bi-shield-lock text-primary me-2"></i>
                        <span class="font-2xs fw-bold text-navy">AUDIT :</span>
                        <p class="font-2xs mb-0 mt-1">Toute modification de prix sera répercutée sur les futures transactions, mais n'affecte pas l'historique des revenus passés.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white border-0 p-4">
                <h5 class="modal-title fw-bold text-uppercase ls-wide small"><i class="bi bi-exclamation-triangle-fill me-2"></i> Danger zone</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3 text-navy fw-bold">Supprimer définitivement le produit :</p>
                <div class="bg-light p-3 rounded-4 mb-4 border text-center">
                    <h6 class="mb-0 fw-bold text-navy">{{ $typeBouteille->marque->nom }} {{ $typeBouteille->taille }}</h6>
                    <small class="text-secondary">Attention aux dépendances de stock et de ventes.</small>
                </div>
                <p class="text-muted small mb-0">La suppression est dangereuse si ce produit a déjà fait l'objet de transactions. Préférez la désactivation du statut.</p>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold font-2xs text-uppercase" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('types-bouteilles.destroy', $typeBouteille) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold font-2xs text-uppercase">Confirmer la suppression</button>
                </form>
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
