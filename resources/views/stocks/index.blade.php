@extends('layouts.app')

@section('title', 'Gestion des Stocks')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Inventaire Global</h1>
        <p class="text-secondary small mb-0">Suivi en temps réel des quantités de bouteilles disponibles</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="d-flex gap-2 justify-content-md-end">
            <a href="{{ route('stocks.mouvements') }}" class="btn btn-outline-navy btn-sm rounded-pill px-3">
                <i class="bi bi-journal-text me-1"></i> Audit Stock
            </a>
            <a href="{{ route('types-bouteilles.create') }}" class="btn btn-navy btn-sm rounded-pill px-4">
                <i class="bi bi-plus-lg me-1"></i> Nouveau Produit
            </a>
        </div>
    </div>
</div>

<!-- Alertes de rupture (si présentes) -->
@php
    $ruptures = $stocks->filter(function($s) { return $s->quantite_pleine < $s->typeBouteille->seuil_alerte; });
@endphp

@if($ruptures->count() > 0)
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
        <div>
            <span class="fw-bold">Alerte Stock Bas :</span> 
            {{ $ruptures->count() }} type(s) de bouteilles sont sous le seuil d'alerte.
        </div>
    </div>
@endif

<!-- Tableau des Stocks -->
<div class="card card-corporate border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">MARQUE & TYPE</th>
                        <th class="text-center">CAPACITÉ</th>
                        <th class="text-center">STOCK PLEIN</th>
                        <th class="text-center">STOCK VIDE</th>
                        <th class="text-center">TOTAL</th>
                        <th class="pe-4 text-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $stock)
                        @php
                            $isLow = $stock->quantite_pleine < $stock->typeBouteille->seuil_alerte;
                            $total = $stock->quantite_pleine + $stock->quantite_vide;
                        @endphp
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-sm bg-light text-navy me-3 fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                                        {{ substr($stock->typeBouteille->marque->nom, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold text-navy small">{{ $stock->typeBouteille->marque->nom }}</p>
                                        <span class="text-secondary small">{{ $stock->typeBouteille->nom }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center small text-secondary">
                                {{ $stock->typeBouteille->taille }}
                            </td>
                            <td class="text-center">
                                <span class="fw-bold {{ $isLow ? 'text-danger animate__animated animate__pulse animate__infinite' : 'text-navy' }}">
                                    {{ $stock->quantite_pleine }}
                                </span>
                                @if($isLow)
                                    <span class="d-block font-2xs text-danger fw-bold">Rupture attendue</span>
                                @endif
                            </td>
                            <td class="text-center text-secondary">
                                {{ $stock->quantite_vide }}
                            </td>
                            <td class="text-center">
                                <span class="badge badge-subtle px-3 py-2 rounded-pill">{{ $total }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('stocks.show', $stock) }}" class="btn btn-sm btn-light border" title="Détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('stocks.edit', $stock) }}" class="btn btn-sm btn-light border" title="Ajuster">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Styles spécifiques pour intensifier l'alerte sur cette page */
    .animate__pulse { --animate-duration: 2s; }
</style>
@endsection
