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
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="type" id="type_echange_simple" 
                                        value="echange_simple" {{ old('type') == 'echange_simple' || !old('type') ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100" for="type_echange_simple">
                                        <i class="bi bi-arrow-left-right"></i> Échange simple
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="type" id="type_echange_type" 
                                        value="echange_type" {{ old('type') == 'echange_type' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning w-100" for="type_echange_type">
                                        <i class="bi bi-arrow-left-right"></i> Échange type
                                    </label>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <input type="radio" class="btn-check" name="type" id="type_achat_simple" 
                                        value="achat_simple" {{ old('type') == 'achat_simple' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100" for="type_achat_simple">
                                        <i class="bi bi-bag-plus"></i> Achat simple
                                    </label>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <input type="radio" class="btn-check" name="type" id="type_echange_differe" 
                                        value="echange_differe" {{ old('type') == 'echange_differe' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-info w-100" for="type_echange_differe">
                                        <i class="bi bi-hourglass-split"></i> Échange différé
                                    </label>
                                </div>
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

                        <!-- Produit et ancien produit (pour échange type) -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="id_type_bouteille" class="form-label fw-bold">
                                    <i class="bi bi-box"></i> Produit reçu *
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
                                <label for="id_type_ancien" class="form-label fw-bold" id="label-ancien" style="display: none;">
                                    <i class="bi bi-box"></i> Produit ancien (échange type)
                                </label>
                                <select class="form-select form-select-lg" 
                                    id="id_type_ancien" name="id_type_ancien" style="display: none;">
                                    <option value="">-- Sélectionner un produit --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('id_type_ancien') == $type->id ? 'selected' : '' }}>
                                            {{ $type->marque->nom }} - {{ $type->nom }} ({{ $type->taille }}L)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Quantité -->
                        <div class="mb-4">
                            <label for="quantite" class="form-label fw-bold">
                                <i class="bi bi-123"></i> Quantité *
                            </label>
                            <input type="number" class="form-control form-control-lg @error('quantite') is-invalid @enderror" 
                                id="quantite" name="quantite" value="{{ old('quantite', 1) }}" min="1" required onchange="updateTotal()">
                            @error('quantite')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
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
                        <p class="mb-2">
                            <span class="badge bg-success">Échange simple</span>
                        </p>
                        <p class="small text-muted">
                            Client retourne une bouteille vide du <strong>même type</strong> et reçoit une bouteille pleine en échange.
                        </p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="mb-2">
                            <span class="badge bg-warning">Échange type</span>
                        </p>
                        <p class="small text-muted">
                            Client retourne une bouteille vide d'un type et reçoit une bouteille pleine d'un <strong>autre type</strong>.
                        </p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <p class="mb-2">
                            <span class="badge bg-primary">Achat simple</span>
                        </p>
                        <p class="small text-muted">
                            Client achète une bouteille pleine <strong>sans retourner de vide</strong>. (Nouveau client ou vente standard)
                        </p>
                    </div>

                    <hr>

                    <div>
                        <p class="mb-2">
                            <span class="badge bg-info">Échange différé</span>
                        </p>
                        <p class="small text-muted">
                            Client retourne une vide d'un type, reçoit une pleine d'un autre type, mais s'engage à retourner la pleine du type initial lors du prochain achat.
                        </p>
                    </div>

                    <hr class="my-3">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Rappel:</strong> Le gérant fixe le prix de chaque transaction manuellement.
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
            
            updateTotal();
            toggleEchangeTypeFields();
        }

        function updateTotal() {
            const prix = parseFloat(document.getElementById('prix_unitaire').value) || 0;
            const qte = parseInt(document.getElementById('quantite').value) || 0;
            const total = prix * qte;
            document.getElementById('montant_total').value = total.toFixed(0);
        }

        function toggleEchangeTypeFields() {
            const type = document.querySelector('input[name="type"]:checked').value;
            const labelAncien = document.getElementById('label-ancien');
            const selectAncien = document.getElementById('id_type_ancien');

            if (type === 'echange_type' || type === 'echange_differe') {
                labelAncien.style.display = 'block';
                selectAncien.style.display = 'block';
            } else {
                labelAncien.style.display = 'none';
                selectAncien.style.display = 'none';
            }
        }

        // Ajouter les listeners pour les radios
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', toggleEchangeTypeFields);
        });

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
