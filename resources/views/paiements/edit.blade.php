@extends('layouts.app')

@section('title', 'Modifier Règlement #' . str_pad($paiement->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Correction de Règlement</h1>
        <p class="text-secondary small mb-0">Modification du paiement ID: {{ str_pad($paiement->id, 8, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('paiements.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Retour à la trésorerie
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-corporate border-0 shadow-sm">
            <div class="card-header bg-navy text-white p-4">
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-pencil-square me-2"></i> Modification des Détails Financement</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('paiements.update', $paiement) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Transaction & Client -->
                        <div class="col-md-6">
                            <label for="transaction_id" class="small text-muted fw-bold text-uppercase mb-2 d-block">Transaction Referente *</label>
                            <select class="form-select @error('transaction_id') is-invalid @enderror" id="transaction_id" name="transaction_id" required>
                                @foreach($transactions as $transaction)
                                    <option value="{{ $transaction->id }}" @selected($paiement->transaction_id == $transaction->id)>
                                        #{{ $transaction->numero_transaction }} - {{ $transaction->client->nom_complet ?? 'Comptoir' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="client_id" class="small text-muted fw-bold text-uppercase mb-2 d-block">Bénéficiaire de la Quittance *</label>
                            <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @selected($paiement->client_id == $client->id)>
                                        {{ $client->nom_complet }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Montant & Mode -->
                        <div class="col-md-6">
                            <label for="montant_paye" class="small text-muted fw-bold text-uppercase mb-2 d-block">Montant Rectifié (F) *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-currency-exchange"></i></span>
                                <input type="number" class="form-control fw-bold text-navy border-start-0 @error('montant_paye') is-invalid @enderror" 
                                       id="montant_paye" name="montant_paye" step="1" min="0" required 
                                       value="{{ old('montant_paye', $paiement->montant_paye) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="mode_paiement" class="small text-muted fw-bold text-uppercase mb-2 d-block">Mode de Paiement *</label>
                            <select class="form-select @error('mode_paiement') is-invalid @enderror" id="mode_paiement" name="mode_paiement" required>
                                <option value="especes" @selected($paiement->mode_paiement == 'especes')>Espèces</option>
                                <option value="orange_money" @selected($paiement->mode_paiement == 'orange_money')>Orange Money</option>
                                <option value="momo" @selected($paiement->mode_paiement == 'momo')>MTN MoMo</option>
                                <option value="virement" @selected($paiement->mode_paiement == 'virement')>Virement</option>
                            </select>
                        </div>

                        <!-- Statut -->
                        <div class="col-md-6">
                            <label for="statut" class="small text-muted fw-bold text-uppercase mb-2 d-block">État de Validation *</label>
                            <select class="form-select fw-bold @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                                <option value="recu" @selected($paiement->statut == 'recu') class="text-primary">Reçu (Encours)</option>
                                <option value="confirme" @selected($paiement->statut == 'confirme') class="text-success">Confirmé (Validé)</option>
                                <option value="refuse" @selected($paiement->statut == 'refuse') class="text-danger">Refusé (Annulé)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="date_paiement" class="small text-muted fw-bold text-uppercase mb-2 d-block">Date Effective *</label>
                            <input type="date" class="form-control @error('date_paiement') is-invalid @enderror" 
                                   id="date_paiement" name="date_paiement" required 
                                   value="{{ old('date_paiement', $paiement->date_paiement->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-12">
                            <label for="notes" class="small text-muted fw-bold text-uppercase mb-2 d-block">Notes Administratives</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Informations complémentaires...">{{ old('notes', $paiement->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Appliquer les Majorations / Corrections
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-corporate border-0 shadow-sm bg-light h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-navy text-uppercase ls-wide small mb-4">Méta-Données de Transaction</h6>
                <div class="small text-secondary">
                    <div class="mb-4">
                        <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Identifiant Pièce</span>
                        <h6 class="fw-bold text-navy mb-0 small">PAY-{{ str_pad($paiement->id, 8, '0', STR_PAD_LEFT) }}</h6>
                    </div>

                    <div class="mb-4">
                        <span class="label text-muted text-uppercase fw-bold font-2xs ls-1 d-block mb-1">Audit Temporel</span>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Créé le</span>
                            <span class="fw-medium text-navy">{{ $paiement->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Dernière Revision</span>
                            <span class="fw-medium text-navy">{{ $paiement->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <hr class="opacity-10 my-4">

                    <div class="bg-white p-3 rounded-4 border border-navy border-opacity-10 mb-4">
                        <i class="bi bi-shield-lock-fill text-warning me-2"></i>
                        <span class="font-2xs fw-bold text-navy">AVERTISSEMENT :</span>
                        <p class="font-2xs mb-0 mt-1">La modification d'un montant de paiement impacte directement le solde restant de la transaction associée. Vérifiez les justificatifs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
