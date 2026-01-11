@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-tag-fill"></i> Marques</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('marques.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Nouvelle marque
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
            $totalMarques = $marques->total();
            $marquesActives = $marques->filter(fn($m) => $m->statut === 'actif')->count();
            $totalTypes = $marques->sum(fn($m) => $m->typesBouteilles()->count());
        @endphp
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total marques</p>
                            <h4 class="mb-0">{{ $totalMarques }}</h4>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="bi bi-tag"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Marques actives</p>
                            <h4 class="mb-0">{{ $marquesActives }}</h4>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total types</p>
                            <h4 class="mb-0">{{ $totalTypes }}</h4>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="bi bi-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des marques -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Liste des marques</h5>
                </div>
                <div class="col-auto">
                    <small class="text-muted">{{ $marques->total() }} marque(s)</small>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th class="text-center">Types</th>
                        <th class="text-center">Statut</th>
                        <th class="text-muted small">Créée le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($marques as $marque)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $marque->nom }}</strong>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">{{ $marque->typesBouteilles()->count() }}</span>
                            </td>
                            <td class="text-center">
                                @if ($marque->statut === 'actif')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i> Actif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle-fill"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $marque->created_at->format('d/m/Y') }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('marques.edit', $marque) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="confirmDelete('{{ route('marques.destroy', $marque) }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                                    <p>Aucune marque enregistrée</p>
                                    <a href="{{ route('marques.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle"></i> En créer une
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
    @if ($marques->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $marques->links() }}
        </div>
    @endif

    <!-- Modal de confirmation -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete(url) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette marque ?')) {
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
