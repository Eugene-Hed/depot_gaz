@extends('layouts.app')

@section('title', 'Détail transaction #' . $transaction->id)

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-receipt"></i> Transaction #{{ $transaction->id }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Type et statut -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations transaction</h5>
                        @switch($transaction->type)
                            @case('echange_simple')
                                <span class="badge bg-success"><i class="bi bi-arrow-left-right"></i> Échange simple</span>
                                @break
                            @case('echange_type')
                                <span class="badge bg-warning"><i class="bi bi-arrow-left-right"></i> Échange type</span>
                                @break
                            @case('achat_simple')
                                <span class="badge bg-primary"><i class="bi bi-bag-plus"></i> Achat simple</span>
                                @break
                            @case('echange_differe')
                                <span class="badge bg-info"><i class="bi bi-hourglass-split"></i> Échange différé</span>
                                @break
                        @endswitch
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Date et heure</p>
                            <p class="mb-0">
                                <strong>{{ $transaction->created_at->format('d/m/Y') }}</strong><br>
                                <small>{{ $transaction->created_at->format('H:i:s') }}</small>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Mode de paiement</p>
                            <p class="mb-0">
                                @if($transaction->mode_paiement)
                                    <span class="badge bg-light text-dark">{{ ucfirst($transaction->mode_paiement) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails produit -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-box"></i> Détails du produit</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Produit</p>
                            <p class="mb-0">
                                <strong>{{ $transaction->typeBouteille->nom }}</strong><br>
                                <small class="text-muted">{{ $transaction->typeBouteille->marque->nom }} • {{ $transaction->typeBouteille->taille }}L</small>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Quantité</p>
                            <p class="mb-0">
                                <strong>{{ $transaction->quantite }}</strong> unité(s)
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Montants -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-cash"></i> Montants</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Prix unitaire</p>
                            <p class="mb-0">
                                <strong>{{ number_format($transaction->prix_unitaire, 0, ',', ' ') }} F</strong>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1">Montant total</p>
                            <p class="mb-0">
                                <strong class="text-success" style="font-size: 1.2rem;">
                                    {{ number_format($transaction->montant_total, 0, ',', ' ') }} F
                                </strong>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-0">
                        <strong>Calcul:</strong> 
                        {{ number_format($transaction->prix_unitaire, 0, ',', ' ') }} F × {{ $transaction->quantite }} = 
                        <span class="text-success">{{ number_format($transaction->montant_total, 0, ',', ' ') }} F</span>
                    </p>
                </div>
            </div>

            <!-- Commentaire -->
            @if($transaction->commentaire)
                <div class="alert alert-info shadow-sm">
                    <h6 class="mb-2"><i class="bi bi-chat-dots"></i> Commentaire</h6>
                    <p class="mb-0">{{ $transaction->commentaire }}</p>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Client -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Client</h5>
                </div>
                <div class="card-body">
                    @if($transaction->client)
                        <p class="mb-2">
                            <strong>{{ $transaction->client->nom }}</strong>
                        </p>
                        <p class="mb-2 text-muted small">
                            <i class="bi bi-telephone"></i> {{ $transaction->client->telephone ?? '-' }}<br>
                            <i class="bi bi-envelope"></i> {{ $transaction->client->email ?? '-' }}
                        </p>
                        <a href="{{ route('clients.show', $transaction->client) ?? '#' }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-right"></i> Voir profil client
                        </a>
                    @else
                        <p class="text-muted">
                            <i class="bi bi-info-circle"></i> Aucun client associé
                        </p>
                    @endif
                </div>
            </div>

            <!-- Administrateur -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-shield-check"></i> Administrateur</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <strong>{{ $transaction->administrateur->name ?? 'Système' }}</strong><br>
                        <small class="text-muted">Enregistré par</small>
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('transactions.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Nouvelle transaction
                        </a>
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list"></i> Voir toutes les transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endsection
