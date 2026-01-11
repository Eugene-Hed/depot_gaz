@extends('layouts.app')

@section('title', 'Paiements')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-cash-coin"></i> Gestion des Paiements</h1>
    <a href="{{ route('paiements.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nouveau Paiement
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        @if($paiements->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Client</th>
                            <th>Transaction</th>
                            <th>Montant Payé</th>
                            <th>Mode Paiement</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paiements as $paiement)
                            <tr>
                                <td>#{{ str_pad($paiement->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $paiement->client->nom ?? 'N/A' }}</td>
                                <td>#{{ $paiement->transaction->numero_transaction ?? 'N/A' }}</td>
                                <td>
                                    <strong>{{ number_format($paiement->montant_paye, 2) }} FCFA</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $paiement->mode_paiement }}</span>
                                </td>
                                <td>
                                    @if($paiement->statut === 'recu')
                                        <span class="badge bg-warning">Reçu</span>
                                    @elseif($paiement->statut === 'confirme')
                                        <span class="badge bg-success">Confirmé</span>
                                    @else
                                        <span class="badge bg-danger">Refusé</span>
                                    @endif
                                </td>
                                <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('paiements.show', $paiement) }}" class="btn btn-sm btn-info" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('paiements.edit', $paiement) }}" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('paiements.destroy', $paiement) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $paiements->links() }}
            </div>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Aucun paiement enregistré
            </div>
        @endif
    </div>
</div>
@endsection
