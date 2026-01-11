@extends('layouts.app')

@section('title', 'Types de bouteilles')

@section('content')
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-box"></i> Types de bouteilles</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('types-bouteilles.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Nouveau type
            </a>
        </div>
    </div>

    <!-- Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques KPI -->
    <div class="row mb-4">
        @php
            $totalTypes = $types->total();
            $typesActifs = $types->filter(fn($t) => $t->statut === 'actif')->count();
            $prixMoyen = $types->avg('prix_vente');
            $seuil = $types->filter(fn($t) => $t->stock && $t->stock->quantite_pleine < $t->seuil_alerte)->count();
        @endphp
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total types</p>
                            <h4 class="mb-0">{{ $totalTypes }}</h4>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="bi bi-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Types actifs</p>
                            <h4 class="mb-0">{{ $typesActifs }}</h4>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Prix moyen</p>
                            <h4 class="mb-0">{{ number_format($prixMoyen ?? 0, 0, ',', ' ') }} F</h4>
                        </div>
                        <div class="text-warning" style="font-size: 2rem;">
                            <i class="bi bi-tag"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Ruptures alerte</p>
                            <h4 class="mb-0">{{ $seuil }}</h4>
                        </div>
                        <div class="text-danger" style="font-size: 2rem;">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des types -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Liste des types</h5>
                </div>
                <div class="col-auto">
                    <small class="text-muted">{{ $types->total() }} type(s)</small>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Marque</th>
                        <th>Taille</th>
                        <th class="text-end">Prix vente</th>
                        <th class="text-end">Recharge</th>
                        <th class="text-center">Seuil alerte</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($types as $type)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $type->marque->nom }}</span>
                            </td>
                            <td>
                                <strong class="text-dark">{{ $type->taille }}L</strong>
                            </td>
                            <td class="text-end">
                                <strong>{{ number_format($type->prix_vente, 0, ',', ' ') }} F</strong>
                            </td>
                            <td class="text-end">
                                <span class="text-muted">{{ number_format($type->prix_recharge ?? 0, 0, ',', ' ') }} F</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $stock = $type->stock;
                                    $isLow = $stock && $stock->quantite_pleine < $type->seuil_alerte;
                                @endphp
                                @if($isLow)
                                    <span class="badge bg-danger">
                                        <i class="bi bi-exclamation-triangle-fill"></i> {{ $type->seuil_alerte }}
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark">{{ $type->seuil_alerte }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($type->statut === 'actif')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i> Actif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle-fill"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('types-bouteilles.edit', $type) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="confirmDelete('{{ route('types-bouteilles.destroy', $type) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <p>Aucun type enregistré</p>
                                    <a href="{{ route('types-bouteilles.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle"></i> En créer un
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($types->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $types->links() }}
        </div>
    @endif

    <!-- Modal de confirmation -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete(url) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce type de bouteille ?')) {
                document.getElementById('deleteForm').action = url;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>

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
