@extends('layouts.app')

@section('title', 'Détail transaction')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-receipt"></i> Détail transaction #{{ $transaction->id }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Date:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Type:</strong> <span class="badge bg-info">{{ $transaction->type }}</span></p>
                            <p><strong>Mode paiement:</strong> {{ $transaction->mode_paiement ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Gérant:</strong> {{ $transaction->administrateur->nom_complet }}</p>
                            <p><strong>Client:</strong> {{ $transaction->client->nom ?? 'Pas de client' }}</p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $transaction->typeBouteille->nom }}</td>
                                    <td>{{ $transaction->quantite }}</td>
                                    <td>{{ number_format($transaction->prix_unitaire, 2, ',', ' ') }} FCFA</td>
                                    <td class="fw-bold">{{ number_format($transaction->montant_total, 2, ',', ' ') }} FCFA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="border-top pt-3">
                        <h5>Total: <span class="text-success">{{ number_format($transaction->montant_total, 2, ',', ' ') }} FCFA</span></h5>
                    </div>

                    @if($transaction->commentaire)
                        <div class="alert alert-info mt-3">
                            <strong>Commentaire:</strong> {{ $transaction->commentaire }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> Voir toutes les transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
