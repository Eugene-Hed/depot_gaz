@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1><i class="fas fa-tag"></i> Marques</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('marques.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle marque
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="marquesTable">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Statut</th>
                        <th>Nombre de types</th>
                        <th>Créée le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($marques as $marque)
                        <tr>
                            <td>
                                <strong>{{ $marque->nom }}</strong>
                            </td>
                            <td>
                                @if ($marque->statut === 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>{{ $marque->typesBouteilles()->count() }}</td>
                            <td>{{ $marque->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('marques.edit', $marque) }}" 
                                       class="btn btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('marques.destroy', $marque) }}" method="POST" 
                                          style="display: inline;" onsubmit="return confirm('Êtes-vous sûr?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Aucune marque enregistrée. <a href="{{ route('marques.create') }}">En créer une</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($marques->hasPages())
        <div class="mt-4">
            {{ $marques->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new DataTable('#marquesTable', {
        language: {
            url: '/js/datatables-fr.json'
        },
        paging: false,
        columnDefs: [
            { orderable: false, targets: 4 }
        ]
    });
});
</script>
@endsection
