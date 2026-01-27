@extends('layouts.app')

@section('title', 'Clients')

@section('content')
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-people"></i> Gestion des Clients</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('clients.create') }}" class="btn btn-success btn-lg">
                <i class="bi bi-person-plus"></i> Ajouter un client
            </a>
        </div>
    </div>

    <!-- Statistiques KPI -->
    <div class="row mb-4">
        @php
            $totalClients = $clients->total();
            $clientsActifs = $clients->filter(fn($c) => $c->statut === 'actif')->count();
            $totalPoints = $clients->sum('points_fidelite');
            $nouveauxClients = $clients->filter(fn($c) => $c->created_at->isCurrentMonth())->count();
        @endphp
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total clients</p>
                            <h4 class="mb-0">{{ $totalClients }}</h4>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="bi bi-people"></i>
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
                            <p class="text-muted mb-0 small">Clients actifs</p>
                            <h4 class="mb-0">{{ $clientsActifs }}</h4>
                        </div>
                        <div class="text-success" style="font-size: 2rem;">
                            <i class="bi bi-person-check"></i>
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
                            <p class="text-muted mb-0 small">Points totaux</p>
                            <h4 class="mb-0">{{ number_format($totalPoints, 0, ',', ' ') }}</h4>
                        </div>
                        <div class="text-warning" style="font-size: 2rem;">
                            <i class="bi bi-star"></i>
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
                            <p class="text-muted mb-0 small">Nouveaux ce mois</p>
                            <h4 class="mb-0">{{ $nouveauxClients }}</h4>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="bi bi-person-fill-add"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des clients -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Liste des clients</h5>
                </div>
                <div class="col-auto">
                    <small class="text-muted">{{ $clients->total() }} client(s)</small>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th class="text-center">Points</th>
                        <th class="text-center">Statut</th>
                        <th>Depuis</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>
                                <strong class="text-dark">{{ $client->nom }}</strong>
                            </td>
                            <td>
                                <a href="tel:{{ $client->telephone }}" class="text-decoration-none">
                                    <i class="bi bi-telephone"></i> {{ $client->telephone }}
                                </a>
                            </td>
                            <td>
                                @if($client->email)
                                    <a href="mailto:{{ $client->email }}" class="text-decoration-none">
                                        <i class="bi bi-envelope"></i> {{ $client->email }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">
                                    <i class="bi bi-star-fill"></i> {{ $client->points_fidelite }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($client->statut === 'actif')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i> Actif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle-fill"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $client->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="confirmDelete('{{ route('clients.destroy', $client) }}')">
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
                                    <p>Aucun client enregistré</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($clients->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $clients->links() }}
        </div>
    @endif

    <!-- Modal de confirmation de suppression -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete(url) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
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
