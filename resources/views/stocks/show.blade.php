@extends('layouts.app')

@section('title', 'Détails du Stock - ' . $stock->typeBouteille->nom)

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-info-circle"></i> {{ $stock->typeBouteille->nom }}</h1>
            <p class="text-muted">Marque: <strong>{{ $stock->typeBouteille->marque->nom }}</strong></p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('stocks.edit', $stock) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Ajuster
            </a>
        </div>
    </div>

    <!-- État actuel -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">Bouteilles pleines</h6>
                    <h3 class="text-success mb-0">{{ $stock->quantite_pleine }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">Bouteilles vides</h6>
                    <h3 class="text-warning mb-0">{{ $stock->quantite_vide }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total</h6>
                    <h3 class="mb-0">{{ $stock->quantite_pleine + $stock->quantite_vide }}</h3>
                    <small class="text-muted">Seuil: {{ $stock->typeBouteille->seuil_alerte }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des mouvements -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Historique des mouvements</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th class="text-center">Pleines</th>
                        <th class="text-center">Vides</th>
                        <th>Motif</th>
                        <th>Commentaire</th>
                        <th>Enregistré par</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mouvements as $mouvement)
                        <tr>
                            <td>
                                <small>
                                    {{ $mouvement->created_at->format('d/m/Y H:i') }}
                                </small>
                            </td>
                            <td>
                                @switch($mouvement->type_mouvement)
                                    @case('entree')
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-down"></i> Entrée
                                        </span>
                                        @break
                                    @case('sortie')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-arrow-up"></i> Sortie
                                        </span>
                                        @break
                                    @case('ajustement')
                                        <span class="badge bg-info">
                                            <i class="bi bi-arrow-repeat"></i> Ajustement
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="text-center">
                                @if($mouvement->type_mouvement == 'entree')
                                    <span class="text-success">+{{ $mouvement->quantite_pleine }}</span>
                                @elseif($mouvement->type_mouvement == 'sortie')
                                    <span class="text-danger">-{{ $mouvement->quantite_pleine }}</span>
                                @else
                                    <span class="text-info">{{ $mouvement->quantite_pleine }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($mouvement->type_mouvement == 'entree')
                                    <span class="text-success">+{{ $mouvement->quantite_vide }}</span>
                                @elseif($mouvement->type_mouvement == 'sortie')
                                    <span class="text-danger">-{{ $mouvement->quantite_vide }}</span>
                                @else
                                    <span class="text-info">{{ $mouvement->quantite_vide }}</span>
                                @endif
                            </td>
                            <td>
                                @if($mouvement->motif)
                                    <small class="badge bg-light text-dark">{{ $mouvement->motif }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                <small>{{ $mouvement->commentaire ?? '-' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $mouvement->administrateur->name ?? 'Système' }}
                                </small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Aucun mouvement enregistré
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $mouvements->links() }}
    </div>

    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endsection
