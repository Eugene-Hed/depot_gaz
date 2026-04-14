@extends('layouts.app')

@section('title', 'Audit des Stocks')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Registre d'Audit Stock</h1>
        <p class="text-secondary small mb-0">Historique chronologique et traçabilité de tous les mouvements d'inventaire</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('stocks.index') }}" class="btn btn-navy btn-sm rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-layers me-1"></i> État des Stocks
        </a>
    </div>
</div>

<!-- Filtres Avancés -->
<div class="card card-corporate border-0 shadow-sm mb-5">
    <div class="card-body p-4">
        <form action="{{ route('stocks.mouvements') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="font-2xs text-muted fw-bold text-uppercase ls-wide mb-2 d-block">Catégorie de Bouteille</label>
                <select name="type_bouteille_id" class="form-select border-0 bg-light rounded-pill px-3 py-2 small">
                    <option value="">Tous les types</option>
                    @foreach($typesBouteilles as $type)
                        <option value="{{ $type->id }}" {{ request('type_bouteille_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->marque->nom }} {{ $type->nom }} ({{ $type->taille }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="font-2xs text-muted fw-bold text-uppercase ls-wide mb-2 d-block">Flux Logistique</label>
                <select name="type_mouvement" class="form-select border-0 bg-light rounded-pill px-3 py-2 small">
                    <option value="">Tous les flux</option>
                    <option value="entree" {{ request('type_mouvement') == 'entree' ? 'selected' : '' }}>Réapprovisionnement (+)</option>
                    <option value="sortie" {{ request('type_mouvement') == 'sortie' ? 'selected' : '' }}>Ventes & Sorties (-)</option>
                    <option value="ajustement" {{ request('type_mouvement') == 'ajustement' ? 'selected' : '' }}>Ajustements Manuel</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="font-2xs text-muted fw-bold text-uppercase ls-wide mb-2 d-block">Période du</label>
                <input type="date" name="date_debut" class="form-control border-0 bg-light rounded-pill px-3 py-2 small" value="{{ request('date_debut') }}">
            </div>
            <div class="col-md-2">
                <label class="font-2xs text-muted fw-bold text-uppercase ls-wide mb-2 d-block">Au</label>
                <input type="date" name="date_fin" class="form-control border-0 bg-light rounded-pill px-3 py-2 small" value="{{ request('date_fin') }}">
            </div>
            <div class="col-md-3 text-end">
                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                    <button type="submit" class="btn btn-navy px-4 fw-bold small">
                        <i class="bi bi-search me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('stocks.mouvements') }}" class="btn btn-light px-3 border-start">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table d'Audit -->
<div class="card card-corporate border-0 shadow-sm overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 text-muted font-2xs text-uppercase">Horodatage</th>
                    <th class="text-muted font-2xs text-uppercase">Spécification Produit</th>
                    <th class="text-center text-muted font-2xs text-uppercase">Type</th>
                    <th class="text-center text-muted font-2xs text-uppercase">Δ Pleins</th>
                    <th class="text-center text-muted font-2xs text-uppercase">Δ Vides</th>
                    <th class="text-muted font-2xs text-uppercase">Motif & Audit Trail</th>
                    <th class="pe-4 text-end text-muted font-2xs text-uppercase">Opérateur</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $mouv)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-navy small">{{ $mouv->created_at->format('d/m/Y') }}</span>
                            <span class="text-muted font-2xs d-block">{{ $mouv->created_at->format('H:i:s') }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-navy small">{{ $mouv->stock->typeBouteille->marque->nom }}</span>
                                <span class="text-secondary font-2xs">{{ $mouv->stock->typeBouteille->nom }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($mouv->type_mouvement == 'entree')
                                <span class="badge badge-subtle badge-success rounded-pill px-3 py-2 font-2xs fw-bold">ENTRÉE</span>
                            @elseif($mouv->type_mouvement == 'sortie')
                                <span class="badge badge-subtle badge-danger rounded-pill px-3 py-2 font-2xs fw-bold">SORTIE</span>
                            @else
                                <span class="badge badge-subtle badge-info rounded-pill px-3 py-2 font-2xs fw-bold">AJUSTEMENT</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php $isPositive = in_array($mouv->type_mouvement, ['entree', 'ajustement']) && $mouv->quantite_pleine > 0; @endphp
                            <span class="fw-extrabold {{ $isPositive ? 'text-success' : 'text-danger' }} small">
                                {{ ($mouv->type_mouvement == 'sortie' ? '-' : ($isPositive ? '+' : '')) }}{{ abs($mouv->quantite_pleine) }}
                            </span>
                        </td>
                        <td class="text-center">
                            @php $isPositiveV = in_array($mouv->type_mouvement, ['entree', 'ajustement']) && $mouv->quantite_vide > 0; @endphp
                            <span class="fw-extrabold {{ $isPositiveV ? 'text-success' : 'text-danger' }} small">
                                {{ ($mouv->type_mouvement == 'sortie' ? '-' : ($isPositiveV ? '+' : '')) }}{{ abs($mouv->quantite_vide) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="small fw-bold text-navy">{{ $mouv->motif ?: 'Opération standard' }}</span>
                                <span class="text-muted font-2xs italic text-truncate" style="max-width: 250px;">{{ $mouv->commentaire ?: 'Aucune note' }}</span>
                            </div>
                        </td>
                        <td class="pe-4 text-end">
                            <span class="font-2xs fw-bold text-navy bg-light rounded-pill px-2 py-1">
                                <i class="bi bi-person-fill me-1"></i>{{ $mouv->administrateur ? $mouv->administrateur->nom_complet : 'Système' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-search fs-1 opacity-10 text-navy d-block mb-3"></i>
                            <span class="text-secondary small italic">Aucun mouvement ne correspond aux filtres appliqués</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mouvements->hasPages())
        <div class="card-footer bg-white p-4">
            {{ $mouvements->links() }}
        </div>
    @endif
</div>

<style>
    .fw-extrabold { font-weight: 800; }
</style>
@endsection
