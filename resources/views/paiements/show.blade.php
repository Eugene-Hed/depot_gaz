@extends('layouts.app')

@section('title', 'Détails du Paiement')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1><i class="bi bi-cash-coin"></i> Détails du Paiement #{{ str_pad($paiement->id, 5, '0', STR_PAD_LEFT) }}</h1>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Informations du Paiement</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Montant Payé:</strong></p>
                        <h4 class="text-success">{{ number_format($paiement->montant_paye, 2) }} FCFA</h4>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Mode de Paiement:</strong></p>
                        <p>{{ ucfirst($paiement->mode_paiement) }}</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Statut:</strong></p>
                        @if($paiement->statut === 'recu')
                            <span class="badge bg-warning">Reçu</span>
                        @elseif($paiement->statut === 'confirme')
                            <span class="badge bg-success">Confirmé</span>
                        @else
                            <span class="badge bg-danger">Refusé</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date du Paiement:</strong></p>
                        <p>{{ $paiement->date_paiement->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

                @if($paiement->notes)
                    <hr>
                    <p><strong>Notes:</strong></p>
                    <p>{{ $paiement->notes }}</p>
                @endif

                @if($paiement->reference_cheque || $paiement->reference_virement || $paiement->reference_carte)
                    <hr>
                    <p><strong>Références:</strong></p>
                    @if($paiement->reference_cheque)
                        <p>Chèque: <code>{{ $paiement->reference_cheque }}</code></p>
                    @endif
                    @if($paiement->reference_virement)
                        <p>Virement: <code>{{ $paiement->reference_virement }}</code></p>
                    @endif
                    @if($paiement->reference_carte)
                        <p>Carte: <code>{{ $paiement->reference_carte }}</code></p>
                    @endif
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Transaction</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>Numéro:</strong> #{{ $paiement->transaction->numero_transaction }}<br>
                    <strong>Type:</strong> {{ ucfirst($paiement->transaction->type) }}<br>
                    <strong>Montant Total:</strong> {{ number_format($paiement->transaction->montant_net, 2) }} FCFA<br>
                    <strong>Montant Payé:</strong> {{ number_format($paiement->transaction->paiements()->sum('montant_paye'), 2) }} FCFA
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Client</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>{{ $paiement->client->nom }}</strong><br>
                    <i class="bi bi-telephone"></i> {{ $paiement->client->telephone }}<br>
                    @if($paiement->client->email)
                        <i class="bi bi-envelope"></i> {{ $paiement->client->email }}<br>
                    @endif
                    <strong>Solde Crédit:</strong> {{ number_format($paiement->client->solde_credit, 2) }} FCFA<br>
                    <strong>Solde Dette:</strong> {{ number_format($paiement->client->solde_dette, 2) }} FCFA
                </p>
                <a href="{{ route('paiements.by-client', $paiement->client) }}" class="btn btn-sm btn-info w-100">
                    <i class="bi bi-list"></i> Voir tous les paiements
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('paiements.edit', $paiement) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <form action="{{ route('paiements.destroy', $paiement) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Confirmer la suppression ?')">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
                <a href="{{ route('paiements.index') }}" class="btn btn-secondary w-100 mt-2">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
