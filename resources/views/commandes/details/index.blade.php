@extends('layouts.app')

@section('title', 'Détails de Commande #' . $commande->numero_commande)

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1><i class="bi bi-receipt"></i> Détails de la Commande #{{ $commande->numero_commande }}</h1>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('commandes.details.create', $commande) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Ajouter une Ligne
        </a>
        <a href="{{ route('fournisseurs.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour aux Commandes
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Informations de la Commande</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <p><strong>Fournisseur:</strong><br>{{ $commande->fournisseur->nom }}</p>
            </div>
            <div class="col-md-3">
                <p><strong>Date Commande:</strong><br>{{ $commande->date_commande?->format('d/m/Y') ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3">
                <p><strong>Date Livraison Prévue:</strong><br>{{ $commande->date_livraison_prevue?->format('d/m/Y') ?? 'N/A' }}</p>
            </div>
            <div class="col-md-3">
                <p><strong>Statut:</strong><br>
                    @if($commande->statut === 'en_attente')
                        <span class="badge bg-warning">En Attente</span>
                    @elseif($commande->statut === 'validee')
                        <span class="badge bg-info">Validée</span>
                    @elseif($commande->statut === 'livree')
                        <span class="badge bg-success">Livrée</span>
                    @else
                        <span class="badge bg-danger">Annulée</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0">Lignes de Commande</h5>
    </div>
    <div class="card-body">
        @if($details->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Type de Bouteille</th>
                            <th>Quantité Commandée</th>
                            <th>Quantité Livrée</th>
                            <th>Restant</th>
                            <th>Prix Unitaire</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $detail)
                            <tr>
                                <td>{{ $detail->typeBouteille->nom }}</td>
                                <td>{{ $detail->quantite_commandee }}</td>
                                <td>{{ $detail->quantite_livree }}</td>
                                <td>{{ $detail->quantite_restante }}</td>
                                <td>{{ number_format($detail->prix_unitaire, 2) }} FCFA</td>
                                <td>{{ number_format($detail->montant_ligne, 2) }} FCFA</td>
                                <td>
                                    @if($detail->statut_ligne === 'en_attente')
                                        <span class="badge bg-warning">En Attente</span>
                                    @elseif($detail->statut_ligne === 'partielle')
                                        <span class="badge bg-info">Partielle</span>
                                    @elseif($detail->statut_ligne === 'livree')
                                        <span class="badge bg-success">Livrée</span>
                                    @else
                                        <span class="badge bg-danger">Annulée</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('commandes.details.edit', [$commande, $detail]) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('commandes.details.destroy', [$commande, $detail]) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4"></th>
                            <th>Total HT:</th>
                            <th>{{ number_format($commande->montant_ht ?? $details->sum('montant_ligne'), 2) }} FCFA</th>
                            <th colspan="2"></th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th>Taxes (18%):</th>
                            <th>{{ number_format($commande->montant_taxes ?? ($commande->montant_ht ?? 0) * 0.18, 2) }} FCFA</th>
                            <th colspan="2"></th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th>Frais Transport:</th>
                            <th>{{ number_format($commande->cout_transport ?? 0, 2) }} FCFA</th>
                            <th colspan="2"></th>
                        </tr>
                        <tr>
                            <th colspan="4"></th>
                            <th>Total TTC:</th>
                            <th class="text-success"><strong>{{ number_format($commande->montant_total, 2) }} FCFA</strong></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Aucune ligne de commande. <a href="{{ route('commandes.details.create', $commande) }}">Ajouter une ligne</a>
            </div>
        @endif
    </div>
</div>
@endsection
