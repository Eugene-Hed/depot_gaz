@extends('layouts.app')

@section('title', 'Nouveau Paiement')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1><i class="bi bi-plus-circle"></i> Enregistrer un Paiement</h1>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <h4 class="alert-heading">Erreurs de validation</h4>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('paiements.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="transaction_id" class="form-label">Transaction <span class="text-danger">*</span></label>
                        <select class="form-select @error('transaction_id') is-invalid @enderror" 
                                id="transaction_id" name="transaction_id" required>
                            <option value="">-- Sélectionner une transaction --</option>
                            @foreach($transactions as $transaction)
                                <option value="{{ $transaction->id }}" @selected(old('transaction_id') == $transaction->id)>
                                    #{{ $transaction->numero_transaction }} - {{ $transaction->client->nom ?? 'N/A' }} ({{ number_format($transaction->montant_net, 2) }} FCFA)
                                </option>
                            @endforeach
                        </select>
                        @error('transaction_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                        <select class="form-select @error('client_id') is-invalid @enderror" 
                                id="client_id" name="client_id" required>
                            <option value="">-- Sélectionner un client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>
                                    {{ $client->nom }} ({{ $client->telephone }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="montant_paye" class="form-label">Montant Payé (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('montant_paye') is-invalid @enderror" 
                               id="montant_paye" name="montant_paye" step="0.01" min="0" required value="{{ old('montant_paye') }}">
                        @error('montant_paye')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mode_paiement" class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                        <select class="form-select @error('mode_paiement') is-invalid @enderror" 
                                id="mode_paiement" name="mode_paiement" required>
                            <option value="">-- Sélectionner un mode --</option>
                            <option value="especes" @selected(old('mode_paiement') == 'especes')>Espèces</option>
                            <option value="cheque" @selected(old('mode_paiement') == 'cheque')>Chèque</option>
                            <option value="virement" @selected(old('mode_paiement') == 'virement')>Virement</option>
                            <option value="carte" @selected(old('mode_paiement') == 'carte')>Carte Bancaire</option>
                        </select>
                        @error('mode_paiement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reference_cheque" class="form-label">Référence Chèque (si applicable)</label>
                        <input type="text" class="form-control" id="reference_cheque" name="reference_cheque" 
                               value="{{ old('reference_cheque') }}" placeholder="Numéro de chèque">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reference_virement" class="form-label">Référence Virement (si applicable)</label>
                        <input type="text" class="form-control" id="reference_virement" name="reference_virement" 
                               value="{{ old('reference_virement') }}" placeholder="Référence virement">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="reference_carte" class="form-label">Référence Carte (si applicable)</label>
                        <input type="text" class="form-control" id="reference_carte" name="reference_carte" 
                               value="{{ old('reference_carte') }}" placeholder="Numéro de transaction">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_paiement" class="form-label">Date du Paiement <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date_paiement') is-invalid @enderror" 
                               id="date_paiement" name="date_paiement" required value="{{ old('date_paiement', date('Y-m-d')) }}">
                        @error('date_paiement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Commentaires optionnels">{{ old('notes') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Enregistrer le Paiement
                </button>
                <a href="{{ route('paiements.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
