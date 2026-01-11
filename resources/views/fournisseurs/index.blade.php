@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1><i class="fas fa-truck"></i> Fournisseurs</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau fournisseur
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
            <table class="table table-hover mb-0" id="fournisseursTable">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fournisseurs as $fournisseur)
                        <tr>
                            <td>
                                <small class="badge bg-dark">{{ $fournisseur->code_fournisseur }}</small>
                            </td>
                            <td>
                                <strong>{{ $fournisseur->nom }}</strong>
                            </td>
                            <td>
                                <a href="tel:{{ $fournisseur->telephone }}">
                                    {{ $fournisseur->telephone }}
                                </a>
                            </td>
                            <td>
                                @if ($fournisseur->email)
                                    <a href="mailto:{{ $fournisseur->email }}">
                                        {{ $fournisseur->email }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $fournisseur->adresse ?? '-' }}</small>
                            </td>
                            <td>
                                @if ($fournisseur->statut === 'actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}" 
                                       class="btn btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" 
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
                                Aucun fournisseur enregistré. <a href="{{ route('fournisseurs.create') }}">En créer un</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($fournisseurs->hasPages())
        <div class="mt-4">
            {{ $fournisseurs->links() }}
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new DataTable('#fournisseursTable', {
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
