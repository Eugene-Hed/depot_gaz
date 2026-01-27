@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="d-flex align-items-center gap-3">
                <i class="bi bi-truck" style="font-size: 2rem; color: #6366f1;"></i>
                <span>Fournisseurs</span>
            </h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Nouveau
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-0 small">Total fournisseurs</p>
                            <h4 class="mb-0">{{ $fournisseurs->total() }}</h4>
                        </div>
                        <div class="text-primary" style="font-size: 2rem;">
                            <i class="bi bi-truck"></i>
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
                            <p class="text-muted mb-0 small">Fournisseurs actifs</p>
                            <h4 class="mb-0">{{ \App\Models\Fournisseur::where('statut', 'actif')->count() }}</h4>
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
                            <p class="text-muted mb-0 small">Fournisseurs inactifs</p>
                            <h4 class="mb-0">{{ \App\Models\Fournisseur::where('statut', 'inactif')->count() }}</h4>
                        </div>
                        <div class="text-secondary" style="font-size: 2rem;">
                            <i class="bi bi-pause-circle"></i>
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
                            <p class="text-muted mb-0 small">Avec email</p>
                            <h4 class="mb-0">{{ \App\Models\Fournisseur::whereNotNull('email')->count() }}</h4>
                        </div>
                        <div class="text-info" style="font-size: 2rem;">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="bi bi-hash"></i> Code</th>
                        <th><i class="bi bi-building"></i> Nom</th>
                        <th><i class="bi bi-telephone"></i> Téléphone</th>
                        <th><i class="bi bi-envelope"></i> Email</th>
                        <th><i class="bi bi-geo-alt"></i> Adresse</th>
                        <th><i class="bi bi-toggle-on"></i> Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fournisseurs as $fournisseur)
                        <tr class="align-middle border-bottom">
                            <td>
                                <span class="badge bg-dark">{{ $fournisseur->code_fournisseur }}</span>
                            </td>
                            <td>
                                <strong>{{ $fournisseur->nom }}</strong>
                            </td>
                            <td>
                                <a href="tel:{{ $fournisseur->telephone }}" class="text-decoration-none">
                                    {{ $fournisseur->telephone }}
                                </a>
                            </td>
                            <td>
                                @if ($fournisseur->email)
                                    <a href="mailto:{{ $fournisseur->email }}" class="text-decoration-none">
                                        <small>{{ $fournisseur->email }}</small>
                                    </a>
                                @else
                                    <span class="text-muted"><small>-</small></span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $fournisseur->adresse ?? '-' }}</small>
                            </td>
                            <td>
                                @if ($fournisseur->statut === 'actif')
                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle"></i> Actif</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary"><i class="bi bi-pause-circle"></i> Inactif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('fournisseurs.edit', $fournisseur) }}" 
                                       class="btn btn-outline-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $fournisseur->id }}"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $fournisseur->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0">
                                    <div class="modal-header bg-danger-subtle border-0">
                                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Confirmation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">Êtes-vous sûr de vouloir supprimer le fournisseur <strong>{{ $fournisseur->nom }}</strong> ?</p>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox" style="font-size: 2rem; opacity: 0.3;"></i>
                                <p class="mt-2">Aucun fournisseur enregistré</p>
                                <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus"></i> Créer un fournisseur
                                </a>
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
@endsection
