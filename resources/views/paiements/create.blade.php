@extends('layouts.app')

@section('title', 'Encaisser un Règlement')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Enregistrer un Paiement</h1>
        <p class="text-secondary small mb-0">Imputation d'un règlement sur une transaction existante</p>
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
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-cash-coin me-2"></i> Formulaire d'Encaissement</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('paiements.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">
                        <!-- Transaction & Client -->
                        <div class="col-md-6">
                            <label for="transaction_id" class="small text-muted fw-bold text-uppercase mb-2 d-block">Transaction Referente *</label>
                            <select class="form-select @error('transaction_id') is-invalid @enderror" id="transaction_id" name="transaction_id" required>
                                <option value="">-- Choisir la transaction --</option>
                                @foreach($transactions as $transaction)
                                    <option value="{{ $transaction->id }}" @selected(old('transaction_id') == $transaction->id)>
                                        #{{ $transaction->numero_transaction }} - {{ $transaction->client->nom_complet ?? 'Comptoir' }} ({{ number_format($transaction->montant_total, 0) }} F)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="client_id" class="small text-muted fw-bold text-uppercase mb-2 d-block">Client Bénéficiaire *</label>
                            <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                <option value="">-- Choisir le client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>
                                        {{ $client->nom_complet }} ({{ $client->telephone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Montant & Mode -->
                        <div class="col-md-6">
                            <label for="montant_paye" class="small text-muted fw-bold text-uppercase mb-2 d-block">Somme Reçue (F) *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-currency-exchange"></i></span>
                                <input type="number" class="form-control fw-bold text-navy border-start-0 @error('montant_paye') is-invalid @enderror" 
                                       id="montant_paye" name="montant_paye" step="1" min="0" required value="{{ old('montant_paye') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="mode_paiement" class="small text-muted fw-bold text-uppercase mb-2 d-block">Canal de Règlement *</label>
                            <select class="form-select form-select-lg @error('mode_paiement') is-invalid @enderror" id="mode_paiement" name="mode_paiement" required>
                                <option value="">-- Mode --</option>
                                <option value="especes" @selected(old('mode_paiement') == 'especes')>Espèces</option>
                                <option value="orange_money" @selected(old('mode_paiement') == 'orange_money')>Orange Money</option>
                                <option value="momo" @selected(old('mode_paiement') == 'momo')>MTN MoMo</option>
                                <option value="virement" @selected(old('mode_paiement') == 'virement')>Virement</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="date_paiement" class="small text-muted fw-bold text-uppercase mb-2 d-block">Date Effective *</label>
                            <input type="date" class="form-control @error('date_paiement') is-invalid @enderror" 
                                   id="date_paiement" name="date_paiement" required value="{{ old('date_paiement', date('Y-m-d')) }}">
                        </div>

                        <div class="col-md-12">
                            <label for="notes" class="small text-muted fw-bold text-uppercase mb-2 d-block">Commentaires / Références</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Ex: Référence transfert, numéro de chèque...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4 mt-5">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Confirmer l'Encaissement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-corporate border-0 shadow-sm bg-light h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-navy text-uppercase ls-wide small mb-4">Informations Trésorerie</h6>
                <div class="small text-secondary">
                    <p class="mb-3"><strong>Validation :</strong> Un paiement enregistré est immédiatement déduit de la balance de la transaction associée.</p>
                    
                    <div class="bg-white p-3 rounded-4 border border-navy border-opacity-10 mb-4">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        <span class="font-2xs fw-bold text-navy">RAPPEL :</span>
                        <p class="font-2xs mb-0 mt-1">Assurez-vous que le montant saisi correspond exactement à la somme perçue pour éviter les écarts de caisse en fin de journée.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
