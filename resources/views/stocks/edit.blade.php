@extends('layouts.app')

@section('title', 'Ajustement Stock - ' . $stock->typeBouteille->nom)

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Régularisation d'Inventaire</h1>
        <p class="text-secondary small mb-0">Ajustement manuel des quantités réelles en stock</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="d-flex gap-2 justify-content-md-end">
            <a href="{{ route('stocks.show', $stock) }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold">
                <i class="bi bi-clock-history me-1"></i> Historique
            </a>
            <a href="{{ route('stocks.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Annuler
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-corporate border-0 shadow-sm mb-4">
            <div class="card-header bg-navy text-white p-4">
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-pencil-square me-2"></i> Formulaire d'Ajustement</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('stocks.update', $stock) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-warning border-0 shadow-none bg-warning-subtle p-3 rounded-4 mb-5">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <small class="fw-bold">Note : L'ajustement direct modifie le stock sans passer par une transaction de vente. Cette action sera logguée dans le registre d'audit.</small>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="quantite_pleine" class="small text-muted fw-bold text-uppercase mb-2 d-block">Bouteilles Pleines (Réel) *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-success-subtle border-0"><i class="bi bi-check-circle-fill text-success"></i></span>
                                <input type="number" class="form-control fw-bold text-navy border-0 bg-light @error('quantite_pleine') is-invalid @enderror" 
                                    id="quantite_pleine" name="quantite_pleine" value="{{ $stock->quantite_pleine }}" required>
                            </div>
                            <small class="text-muted font-2xs italic mt-2 d-block">Valeur système actuelle : {{ $stock->quantite_pleine }}</small>
                        </div>

                        <div class="col-md-6">
                            <label for="quantite_vide" class="small text-muted fw-bold text-uppercase mb-2 d-block">Bouteilles Vides (Réel) *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-warning-subtle border-0"><i class="bi bi-circle text-warning"></i></span>
                                <input type="number" class="form-control fw-bold text-navy border-0 bg-light @error('quantite_vide') is-invalid @enderror" 
                                    id="quantite_vide" name="quantite_vide" value="{{ $stock->quantite_vide }}" required>
                            </div>
                            <small class="text-muted font-2xs italic mt-2 d-block">Valeur système actuelle : {{ $stock->quantite_vide }}</small>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="commentaire" class="small text-muted fw-bold text-uppercase mb-2 d-block">Motif de l'ajustement</label>
                        <textarea class="form-control fw-medium @error('commentaire') is-invalid @enderror" id="commentaire" name="commentaire" rows="3" 
                            placeholder="Ex: Correction suite inventaire physique, retour casse non compté...">{{ old('commentaire') }}</textarea>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Mettre à Jour l'Inventaire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-corporate border-0 shadow-sm bg-light h-100">
            <div class="card-body p-4 text-center">
                <div class="mb-4">
                    <img src="{{ $stock->typeBouteille->image_url }}" alt="{{ $stock->typeBouteille->taille }}" style="max-height: 120px; object-fit: contain;">
                </div>
                <h6 class="fw-bold text-navy mb-1">{{ $stock->typeBouteille->marque->nom }}</h6>
                <p class="text-secondary small mb-4">{{ $stock->typeBouteille->nom }} ({{ $stock->typeBouteille->taille }})</p>
                
                <hr class="opacity-10 mb-4">
                
                <div class="text-start mb-4">
                    <p class="small text-muted fw-bold text-uppercase mb-3">Répartition Actuelle</p>
                    @php
                        $total = $stock->quantite_pleine + $stock->quantite_vide;
                        $tauxPlein = $total > 0 ? ($stock->quantite_pleine / $total) * 100 : 0;
                    @endphp
                    <div class="progress rounded-pill mb-2" style="height: 20px;">
                        <div class="progress-bar bg-success" style="width: {{ $tauxPlein }}%">{{ round($tauxPlein) }}%</div>
                        <div class="progress-bar bg-warning" style="width: {{ 100 - $tauxPlein }}%"></div>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-success fw-bold">Plein: {{ $stock->quantite_pleine }}</span>
                        <span class="text-warning fw-bold">Vide: {{ $stock->quantite_vide }}</span>
                    </div>
                </div>

                <div class="bg-white p-3 rounded-4 border border-navy border-opacity-10 text-start">
                    <i class="bi bi-shield-check text-success me-2"></i>
                    <span class="font-2xs fw-bold text-navy">AUDIT :</span>
                    <p class="font-2xs mb-0 mt-1">L'ajusteur est responsable de l'exactitude des chiffres. Toute modification est tracée par utilisateur et horodatée.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
