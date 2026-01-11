@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1><i class="fas fa-bottle-water"></i> Types de bouteilles</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('types-bouteilles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau type
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
            <table class="table table-hover mb-0" id="typesTable">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Marque</th>
                        <th>Taille</th>
                        <th>Prix vente</th>
                        <th>Seuil alerte</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($types as $type)
                        <tr>
                            <td>
                                <strong>{{ $type->nom }}</strong>
                            </td>
                            <td>{{ $type->marque->nom }}</td>
                            <td>{{ $type->taille }}</td>
                            <td>
                                <span class="badge bg-info">{{ number_format($type->prix_vente, 0, ',', ' ') }} FCFA</span>
                            </td>
                            <td>{{ $type->seuil_alerte }} unités</td>
                            <td>
                                @if ($type->statut === 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('types-bouteilles.edit', $type) }}" 
                                       class="btn btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('types-bouteilles.destroy', $type) }}" method="POST" 
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
                            <td colspan="7" class="text-center text-muted py-4">
                                Aucun type enregistré. <a href="{{ route('types-bouteilles.create') }}">En créer un</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($types->hasPages())
        <div class="mt-4">
            {{ $types->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new DataTable('#typesTable', {
        language: {
            url: '/js/datatables-fr.json'
        },
        paging: false,
        columnDefs: [
            { orderable: false, targets: 6 }
        ]
    });
});
</script>
@endsection
