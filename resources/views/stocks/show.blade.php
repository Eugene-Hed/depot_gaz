@extends('layouts.app')

@section('title', 'Détails du Stock - ' . $stock->typeBouteille->nom)

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Mouvements de Stock</h1>
        <p class="text-secondary small mb-0">{{ $stock->typeBouteille->marque->nom }} - {{ $stock->typeBouteille->nom }} ({{ $stock->typeBouteille->taille }})</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="d-flex gap-2 justify-content-md-end">
            <a href="{{ route('stocks.edit', $stock) }}" class="btn btn-navy btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-pencil me-1"></i> Ajuster le stock
            </a>
            <a href="{{ route('stocks.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card card-corporate border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4 border-start border-4 border-success">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-circle-sm bg-success bg-opacity-10 text-success me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                        <i class="bi bi-check2-circle fs-5"></i>
                    </div>
                    <span class="small text-muted fw-bold text-uppercase ls-wide font-2xs">Unités Pleines</span>
                </div>
                <h2 class="fw-extrabold text-navy mb-0">{{ $stock->quantite_pleine }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-corporate border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4 border-start border-4 border-warning">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-circle-sm bg-warning bg-opacity-10 text-warning me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                        <i class="bi bi-circle fs-5"></i>
                    </div>
                    <span class="small text-muted fw-bold text-uppercase ls-wide font-2xs">Unités Vides</span>
                </div>
                <h2 class="fw-extrabold text-navy mb-0">{{ $stock->quantite_vide }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-corporate border-0 shadow-sm overflow-hidden">
            <div class="card-body p-4 border-start border-4 border-navy">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-circle-sm bg-navy bg-opacity-10 text-navy me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px;">
                        <i class="bi bi-layers fs-5"></i>
                    </div>
                    <span class="small text-muted fw-bold text-uppercase ls-wide font-2xs">Capacité Totale</span>
                </div>
                <h2 class="fw-extrabold text-navy mb-0">{{ $stock->quantite_pleine + $stock->quantite_vide }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card card-corporate border-0 shadow-sm overflow-hidden mb-4">
    <div class="card-header bg-white border-bottom p-4">
        <div class="row align-items-center">
            <div class="col">
                <h6 class="mb-0 fw-bold text-navy text-uppercase ls-wide small"><i class="bi bi-clock-history me-2"></i> Historique de Traçabilité</h6>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 text-muted font-2xs text-uppercase">Date & Heure</th>
                    <th class="text-muted font-2xs text-uppercase">Type</th>
                    <th class="text-center text-muted font-2xs text-uppercase">Δ Pleins</th>
                    <th class="text-center text-muted font-2xs text-uppercase">Δ Vides</th>
                    <th class="text-muted font-2xs text-uppercase">Motif</th>
                    <th class="text-muted font-2xs text-uppercase">Opérateur</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $mouvement)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-navy small">{{ $mouvement->created_at->format('d/m/Y') }}</span>
                            <span class="text-muted font-2xs d-block">{{ $mouvement->created_at->format('H:i:s') }}</span>
                        </td>
                        <td>
                            @switch($mouvement->type_mouvement)
                                @case('entree')
                                    <span class="badge badge-subtle badge-success rounded-pill px-3 py-2 font-2xs fw-bold"><i class="bi bi-box-arrow-in-down me-1"></i> RÉAPPRO</span>
                                    @break
                                @case('sortie')
                                    <span class="badge badge-subtle badge-danger rounded-pill px-3 py-2 font-2xs fw-bold"><i class="bi bi-truck me-1"></i> VENTE</span>
                                    @break
                                @case('ajustement')
                                    <span class="badge badge-subtle badge-info rounded-pill px-3 py-2 font-2xs fw-bold"><i class="bi bi-gear-wide-connected me-1"></i> AJUSTEMENT</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="text-center">
                            @if($mouvement->quantite_pleine > 0)
                                <span class="fw-extrabold text-success small">+{{ $mouvement->quantite_pleine }}</span>
                            @elseif($mouvement->quantite_pleine < 0)
                                <span class="fw-extrabold text-danger small">{{ $mouvement->quantite_pleine }}</span>
                            @else
                                <span class="text-muted font-2xs">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($mouvement->quantite_vide > 0)
                                <span class="fw-extrabold text-warning small">+{{ $mouvement->quantite_vide }}</span>
                            @elseif($mouvement->quantite_vide < 0)
                                <span class="fw-extrabold text-danger small">{{ $mouvement->quantite_vide }}</span>
                            @else
                                <span class="text-muted font-2xs">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="small fw-bold text-navy">{{ $mouvement->motif ?: 'Opération diverse' }}</span>
                                <span class="text-muted font-2xs italic text-truncate" style="max-width: 200px;">{{ $mouvement->commentaire }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="font-2xs fw-bold text-navy bg-light rounded-pill px-2 py-1">{{ $mouvement->administrateur ? $mouvement->administrateur->nom_complet : 'Système' }}</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inboxes fs-1 opacity-10 text-navy d-block mb-3"></i>
                            <span class="text-secondary small italic">Aucune archive disponible pour ce produit</span>
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
