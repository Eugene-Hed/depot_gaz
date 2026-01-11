@extends('layouts.app')

@section('title', 'Clients')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-people"></i> Gestion des Clients</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('clients.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Ajouter un client
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Points Fidélité</th>
                        <th>Statut</th>
                        <th>Depuis</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td><strong>{{ $client->nom }}</strong></td>
                            <td>{{ $client->telephone }}</td>
                            <td>{{ $client->email ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $client->points_fidelite }} pts</span>
                            </td>
                            <td>
                                @if($client->statut === 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>{{ $client->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Aucun client</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $clients->links() }}
    </div>
@endsection
