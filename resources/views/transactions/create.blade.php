@extends('layouts.app')

@section('title', 'Nouvelle transaction')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="bi bi-plus-circle"></i> Nouvelle transaction</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Formulaire de transaction</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf

                        <!-- Type de transaction -->
                        <div class="mb-4">
                            <label for="type" class="form-label fw-bold">
                                <i class="bi bi-arrow-left-right"></i> Type de transaction *
                            </label>
                            <div class="btn-group d-flex" role="group" style="gap: 0.5rem;">
                                <input type="radio" class="btn-check" name="type" id="type_vente" 
                                    value="vente" {{ old('type') == 'vente' || !old('type') ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="type_vente">
                                    <i class="bi bi-bag-check"></i> Vente
                                </label>

                                <input type="radio" class="btn-check" name="type" id="type_retour" 
                                    value="retour" {{ old('type') == 'retour' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="type_retour">
                                    <i class="bi bi-arrow-counterclockwise"></i> Retour
                                </label>

                                <input type="radio" class="btn-check" name="type" id="type_recharge" 
                                    value="recharge" {{ old('type') == 'recharge' ? 'checked' : '' }}>
                                <label class="btn btn-outline-info" for="type_recharge">
                                    <i class="bi bi-arrow-down"></i> Recharge
                                </label>

                                <input type="radio" class="btn-check" name="type" id="type_echange" 
                                    value="echange" {{ old('type') == 'echange' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning" for="type_echange">
                                    <i class="bi bi-arrow-left-right"></i> Échange
                                </label>

                                <input type="radio" class="btn-check" name="type" id="type_consigne" 
                                    value="consigne" {{ old('type') == 'consigne' ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary" for="type_consigne">
                                    <i class="bi bi-key"></i> Consigne
                                </label>
                            </div>
                            @error('type')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Client -->
                        <div class="mb-4">
                            <label for="id_client" class="form-label fw-bold">
                                <i class="bi bi-person"></i> Client (optionnel)
                            </label>
                            <select class="form-select form-select-lg @error('id_client') is-invalid @enderror" 
                                id="id_client" name="id_client">
                                <option value="">-- Aucun client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('id_client') == $client->id ? 'selected' : '' }}>
                                        {{ $client->nom }} ({{ $client->telephone ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_client')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Produit et quantité -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_type_bouteille" class="form-label fw-bold">
                                    <i class="bi bi-box"></i> Produit *
                                </label>
                                <select class="form-select form-select-lg @error('id_type_bouteille') is-invalid @enderror" 
                                    id="id_type_bouteille" name="id_type_bouteille" required onchange="updatePrice()">
                                    <option value="">-- Sélectionner un produit --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" 
                                            data-prix="{{ $type->prix_vente ?? 0 }}"
                                            data-stock="{{ $type->stock->quantite_pleine ?? 0 }}"
                                            {{ old('id_type_bouteille') == $type->id ? 'selected' : '' }}>
                                            {{ $type->marque->nom }} - {{ $type->nom }} ({{ $type->taille }}L)
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_type_bouteille')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    Stock disponible: <span id="stock-info" class="badge bg-info">0</span>
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label for="quantite" class="form-label fw-bold">
                                    <i class="bi bi-123"></i> Quantité *
                                </label>
                                <input type="number" class="form-control form-control-lg @error('quantite') is-invalid @enderror" 
                                    id="quantite" name="quantite" value="{{ old('quantite', 1) }}" min="1" required onchange="updateTotal()">
                                @error('quantite')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="prix_unitaire" class="form-label fw-bold">
                                    <i class="bi bi-tag"></i> Prix unitaire *
                                </label>
                                <input type="number" class="form-control form-control-lg @error('prix_unitaire') is-invalid @enderror" 
                                    id="prix_unitaire" name="prix_unitaire" value="{{ old('prix_unitaire', 0) }}" 
                                    step="1" required onchange="updateTotal()">
                                @error('prix_unitaire')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    Prix catalogue: <span id="prix-info" class="badge bg-secondary">0 F</span>
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label for="montant_total" class="form-label fw-bold">
                                    <i class="bi bi-cash"></i> Montant total *
                                </label>
                                <input type="number" class="form-control form-control-lg @error('montant_total') is-invalid @enderror" 
                                    id="montant_total" name="montant_total" value="{{ old('montant_total', 0) }}" 
                                    step="1" required readonly>
                                @error('montant_total')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Paiement et commentaire -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="mode_paiement" class="form-label fw-bold">
                                    <i class="bi bi-wallet2"></i> Mode de paiement
                                </label>
                                <select class="form-select" id="mode_paiement" name="mode_paiement">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="especes" {{ old('mode_paiement') == 'especes' ? 'selected' : '' }}>
                                        <i class="bi bi-coin"></i> Espèces
                                    </option>
                                    <option value="cheque" {{ old('mode_paiement') == 'cheque' ? 'selected' : '' }}>
                                        <i class="bi bi-receipt"></i> Chèque
                                    </option>
                                    <option value="carte" {{ old('mode_paiement') == 'carte' ? 'selected' : '' }}>
                                        <i class="bi bi-credit-card"></i> Carte
                                    </option>
                                    <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>
                                        <i class="bi bi-bank"></i> Virement
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="commentaire" class="form-label fw-bold">
                                    <i class="bi bi-chat-dots"></i> Commentaire
                                </label>
                                <input type="text" class="form-control" id="commentaire" name="commentaire" 
                                    value="{{ old('commentaire') }}" placeholder="Notes supplémentaires...">
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Enregistrer transaction
                            </button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel informatif -->
        <div class="col-md-4">
            <div class="card shadow-sm bg-light">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Types de transactions</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-2"><strong class="text-success">Vente</strong></p>
                        <p class="small text-muted">Transaction de vente classique. Le client reçoit les bouteilles.</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="mb-2"><strong class="text-danger">Retour</strong></p>
                        <p class="small text-muted">Retour de bouteilles vides par le client. Augmente le stock de vides.</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="mb-2"><strong class="text-info">Recharge</strong></p>
                        <p class="small text-muted">Remplissage de bouteilles vides. Convertit vides → pleines.</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="mb-2"><strong class="text-warning">Échange</strong></p>
                        <p class="small text-muted">Échange de bouteilles. Client retourne des vides et reçoit des pleines.</p>
                    </div>

                    <hr>

                    <div>
                        <p class="mb-2"><strong class="text-secondary">Consigne</strong></p>
                        <p class="small text-muted">Consignation de bouteilles. Dépôt sans vente immédiate.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updatePrice() {
            const select = document.getElementById('id_type_bouteille');
            const option = select.options[select.selectedIndex];
            const prix = option.dataset.prix || 0;
            const stock = option.dataset.stock || 0;
            
            document.getElementById('prix-info').textContent = prix + ' F';
            document.getElementById('stock-info').textContent = stock;
            
            if (parseInt(document.getElementById('prix_unitaire').value) === 0) {
                document.getElementById('prix_unitaire').value = prix;
            }
            updateTotal();
        }

        function updateTotal() {
            const prix = parseFloat(document.getElementById('prix_unitaire').value) || 0;
            const qte = parseInt(document.getElementById('quantite').value) || 0;
            const total = prix * qte;
            document.getElementById('montant_total').value = total.toFixed(0);
        }

        // Initialiser au chargement
        updatePrice();
    </script>

    <style>
        .btn-check:checked + .btn {
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.15);
        }
        
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endsection
