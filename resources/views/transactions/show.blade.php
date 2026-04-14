@extends('layouts.app')

@section('title', 'Détails Transaction #' . $transaction->id)

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Pièce de Caisse #{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</h1>
        <p class="text-secondary small mb-0">Visualisation détaillée de l'opération enregistrée</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="dropdown d-inline-block">
            <button class="btn btn-navy btn-sm rounded-pill px-4 dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-printer me-1"></i> Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 rounded-4">
                <li><a class="dropdown-item rounded-3 small fw-bold py-2" href="#"><i class="bi bi-file-pdf me-2 text-danger"></i> Télécharger PDF</a></li>
                <li><a class="dropdown-item rounded-3 small fw-bold py-2" href="#"><i class="bi bi-chat-dots me-2 text-success"></i> Envoyer par WhatsApp</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item rounded-3 small fw-bold py-2" href="{{ route('transactions.index') }}"><i class="bi bi-list me-2"></i> Voir toutes</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Ticket de Transaction -->
        <div class="card card-corporate border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-header bg-navy text-white p-4 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small">Justificatif d'opération</h6>
                @php
                    $typeLabels = [
                        'echange_simple' => 'ÉCHANGE STANDARD',
                        'echange_type' => 'ÉCHANGE SPÉCIFIANT',
                        'achat_simple' => 'ACHAT SIMPLE',
                    ];
                @endphp
                <span class="badge bg-white text-navy rounded-pill px-3 font-2xs fw-bold">{{ $typeLabels[$transaction->type] ?? 'DIVERS' }}</span>
            </div>
            <div class="card-body p-0">
                <div class="p-4 border-bottom bg-light bg-opacity-50">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span class="small text-muted fw-bold text-uppercase font-2xs d-block mb-1">Horodatage</span>
                            <p class="fw-bold mb-0 text-navy">{{ $transaction->created_at->format('d/m/Y') }} à {{ $transaction->created_at->format('H:i') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="small text-muted fw-bold text-uppercase font-2xs d-block mb-1">Mode de Règlement</span>
                            <span class="badge badge-subtle rounded-pill px-3 py-2 text-navy fw-bold text-uppercase font-2xs">
                                <i class="bi bi-wallet2 me-1"></i> {{ $transaction->mode_paiement ?: 'En attente' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="bg-light rounded-4">
                            <tr>
                                <th class="ps-3 text-muted font-2xs text-uppercase">Désignation</th>
                                <th class="text-center text-muted font-2xs text-uppercase">PU (F)</th>
                                <th class="text-center text-muted font-2xs text-uppercase">Qté</th>
                                <th class="pe-3 text-end text-muted font-2xs text-uppercase">Total (F)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-3 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm bg-light text-navy me-3 fw-bold d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; border-radius: 12px;">
                                            <i class="bi bi-box-seam fs-4"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold text-navy">{{ $transaction->typeBouteille->nom }}</p>
                                            <span class="text-secondary font-2xs">{{ $transaction->typeBouteille->marque->nom }} • {{ $transaction->typeBouteille->taille }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold text-navy small">{{ number_format($transaction->prix_unitaire, 0, ',', ' ') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-navy border px-2">× {{ $transaction->quantite }}</span>
                                </td>
                                <td class="pe-3 text-end fw-extrabold text-navy fs-5">
                                    {{ number_format($transaction->montant_total, 0, ',', ' ') }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <td colspan="3" class="text-end py-4 text-muted small fw-bold text-uppercase">Montant Net à Payer</td>
                                <td class="pe-3 text-end py-4">
                                    <h3 class="fw-extrabold text-navy mb-0">{{ number_format($transaction->montant_total, 0, ',', ' ') }} <small class="text-muted font-2xs">FCFA</small></h3>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($transaction->commentaire)
            <div class="card card-corporate border-0 shadow-sm bg-light p-4">
                <h6 class="fw-bold text-navy text-uppercase ls-wide font-2xs mb-2">Note d'opération</h6>
                <p class="mb-0 text-secondary italic small">"{{ $transaction->commentaire }}"</p>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Bloc Client -->
        <div class="card card-corporate border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-0 p-4 pb-0">
                <h6 class="mb-0 fw-bold text-navy text-uppercase ls-wide small">Profil Client</h6>
            </div>
            <div class="card-body p-4">
                @if($transaction->client)
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-circle-sm bg-light-navy text-navy me-3 fw-bold d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border-radius: 16px;">
                            {{ substr($transaction->client->nom_complet, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="fw-bold text-navy mb-0">{{ $transaction->client->nom_complet }}</h6>
                            <span class="text-secondary font-2xs">{{ $transaction->client->telephone }}</span>
                        </div>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('clients.show', $transaction->client) }}" class="btn btn-sm btn-light border text-navy fw-bold font-2xs text-uppercase rounded-pill">Consulter le dossier</a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-person-x fs-1 opacity-10 text-navy mb-2"></i>
                        <p class="text-muted small italic mb-0">Aucun client rattaché<br>(Vente au comptoir)</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Trateur -->
        <div class="card card-corporate border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-circle-sm bg-success bg-opacity-10 text-success me-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <span class="font-2xs text-muted text-uppercase fw-bold d-block">Traitée par</span>
                        <h6 class="fw-bold text-navy mb-0 small">{{ $transaction->administrateur ? $transaction->administrateur->nom_complet : 'Système' }}</h6>
                    </div>
                </div>
                <div class="bg-light p-3 rounded-4 small text-secondary">
                    <i class="bi bi-info-circle me-1"></i> Identifiant de trace : <code>TXN-{{ str_pad($transaction->id, 8, '0', STR_PAD_LEFT) }}</code>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-extrabold { font-weight: 800; }
</style>
@endsection
