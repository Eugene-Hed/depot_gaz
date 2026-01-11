@extends('layouts.app')

@section('title', 'Paiements de ' . $client->nom)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1><i class="bi bi-person-check"></i> Paiements de {{ $client->nom }}</h1>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <p class="text-white-50 mb-0">Solde Crédit</p>
                <h3>{{ number_format($client->solde_credit, 2) }} FCFA</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center bg-danger text-white">
            <div class="card-body">
                <p class="text-white-50 mb-0">Solde Dette</p>
                <h3>{{ number_format($client->solde_dette, 2) }} FCFA</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Historique des Paiements</h5>
        <a href="{{ route('paiements.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
    <div class="card-body">
        @if($paiements->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Transaction</th>
                            <th>Montant</th>
                            <th>Mode</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paiements as $paiement)
                            <tr>
                                <td>#{{ str_pad($paiement->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>#{{ $paiement->transaction->numero_transaction }}</td>
                                <td>{{ number_format($paiement->montant_paye, 2) }} FCFA</td>
                                <td><span class="badge bg-info">{{ $paiement->mode_paiement }}</span></td>
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
                                    <a href="{{ route('paiements.show', $paiement) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
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
                <i class="bi bi-info-circle"></i> Aucun paiement enregistré pour ce client
            </div>
        @endif
    </div>
</div>
@endsection
