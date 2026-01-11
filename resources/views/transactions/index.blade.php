@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-cart-check"></i> Transactions</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouvelle vente
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Bouteille</th>
                        <th>Qté</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trans)
                        <tr>
                            <td>{{ $trans->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $trans->type === 'vente' ? 'success' : 'info' }}">
                                    {{ $trans->type }}
                                </span>
                            </td>
                            <td>{{ $trans->typeBouteille->nom }}</td>
                            <td>{{ $trans->quantite }}</td>
                            <td>{{ $trans->client->nom ?? '-' }}</td>
                            <td class="fw-bold">{{ number_format($trans->montant_total, 2, ',', ' ') }} FCFA</td>
                            <td>{{ $trans->mode_paiement ?? '-' }}</td>
                            <td>
                                <a href="{{ route('transactions.show', $trans) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Aucune transaction</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $transactions->links() }}
    </div>
@endsection
