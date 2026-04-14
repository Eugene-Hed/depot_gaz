@extends('layouts.app')

@section('title', 'Nouvelle Opération')

@section('content')
<div class="row mb-5 align-items-end">
    <div class="col-md-8">
        <h1 class="h3 fw-bold text-navy mb-1 text-uppercase ls-wide">Enregistrer une Opération</h1>
        <p class="text-secondary small mb-0">Ventes, échanges et régularisations de stock</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('transactions.index') }}" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Retour au journal
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-corporate border-0 shadow-sm">
            <div class="card-header bg-navy text-white p-4">
                <h6 class="mb-0 fw-bold text-uppercase ls-wide small"><i class="bi bi-receipt me-2"></i> Détails de la Transaction</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
                    @csrf

                    <!-- Type de transaction -->
                    <div class="mb-5">
                        <label class="small text-muted fw-bold text-uppercase mb-3 d-block">1. Nature de l'opération</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="type" id="type_echange_simple" 
                                    value="echange_simple" {{ old('type') == 'echange_simple' || !old('type') ? 'checked' : '' }}>
                                <label class="btn btn-outline-navy w-100 py-3 rounded-4 d-flex flex-column align-items-center" for="type_echange_simple">
                                    <i class="bi bi-arrow-left-right mb-2 fs-5"></i>
                                    <span class="fw-bold small">Échange Standard</span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="type" id="type_echange_type" 
                                    value="echange_type" {{ old('type') == 'echange_type' ? 'checked' : '' }}>
                                <label class="btn btn-outline-navy w-100 py-3 rounded-4 d-flex flex-column align-items-center" for="type_echange_type">
                                    <i class="bi bi-shuffle mb-2 fs-5"></i>
                                    <span class="fw-bold small">Échange Spécifiant</span>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="type" id="type_achat_simple" 
                                    value="achat_simple" {{ old('type') == 'achat_simple' ? 'checked' : '' }}>
                                <label class="btn btn-outline-navy w-100 py-3 rounded-4 d-flex flex-column align-items-center" for="type_achat_simple">
                                    <i class="bi bi-cart-plus mb-2 fs-5"></i>
                                    <span class="fw-bold small">Achat Simple</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Client Section -->
                    <div class="mb-5 p-4 bg-light rounded-4 border">
                        <label for="id_client" class="small text-muted fw-bold text-uppercase mb-2 d-block">2. Identification Client</label>
                        <select class="form-select form-select-lg border-0 shadow-none @error('id_client') is-invalid @enderror" 
                            id="id_client" name="id_client" style="background-color: transparent; font-weight: 600;">
                            <option value="">Client de passage (Anonyme)</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('id_client') == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom_complet }} ({{ $client->telephone ?: 'Sans Tél' }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_client')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Produits Section -->
                    <div class="mb-5">
                        <label class="small text-muted fw-bold text-uppercase mb-3 d-block">3. Sélection des articles</label>
                        <div class="row g-4">
                            <div class="col-md-6" id="container-produit">
                                <label for="id_type_bouteille" class="small fw-bold text-navy mb-2 d-block">Article Sortant (Plein)</label>
                                <select class="form-select @error('id_type_bouteille') is-invalid @enderror" 
                                    id="id_type_bouteille" name="id_type_bouteille" required onchange="updatePrice()">
                                    <option value="">-- Choisir le produit --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" 
                                            data-prix="{{ $type->prix_vente ?? 0 }}"
                                            data-recharge="{{ $type->prix_recharge ?? 0 }}"
                                            data-consigne="{{ $type->prix_consigne ?? 0 }}"
                                            data-stock="{{ $type->stock->quantite_pleine ?? 0 }}"
                                            {{ old('id_type_bouteille') == $type->id ? 'selected' : '' }}>
                                            {{ $type->marque->nom }} - {{ $type->nom }} ({{ $type->taille }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="mt-2 text-end">
                                    <span class="badge badge-subtle font-2xs">Stock: <span id="stock-info">0</span></span>
                                </div>
                            </div>

                            <div class="col-md-6" id="container-ancien" style="display: none;">
                                <label for="id_type_ancien" class="small fw-bold text-navy mb-2 d-block">Bouteille Vide Rendue</label>
                                <select class="form-select" id="id_type_ancien" name="id_type_ancien" onchange="updatePrice()">
                                    <option value="">-- Modèle retourné --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" 
                                            data-consigne="{{ $type->prix_consigne ?? 0 }}"
                                            {{ old('id_type_ancien') == $type->id ? 'selected' : '' }}>
                                            {{ $type->marque->nom }} - {{ $type->taille }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tarification -->
                    <div class="mb-5">
                        <label class="small text-muted fw-bold text-uppercase mb-3 d-block">4. Quantité & Prix</label>
                        <div class="row g-4 align-items-center">
                            <div class="col-md-3">
                                <label for="quantite" class="small fw-bold text-navy mb-2 d-block">Quantité</label>
                                <input type="number" class="form-control text-center fw-bold" id="quantite" name="quantite" value="{{ old('quantite', 1) }}" min="1" required onchange="updateTotal()">
                            </div>
                            <div class="col-md-4">
                                <label for="prix_unitaire" class="small fw-bold text-navy mb-2 d-block">Prix Unitaire (F)</label>
                                <input type="number" class="form-control fw-bold text-navy" id="prix_unitaire" name="prix_unitaire" value="{{ old('prix_unitaire', 0) }}" step="1" required onchange="updateTotal()">
                            </div>
                            <div class="col-md-5">
                                <label class="small fw-bold text-navy mb-2 d-block">Total à Payer</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-lg fw-extrabold text-navy bg-light border-0" id="montant_total_display" value="0 F" readonly>
                                    <input type="hidden" name="montant_total" id="montant_total_hidden">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Finalisation -->
                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <label for="mode_paiement" class="small text-muted fw-bold text-uppercase mb-2 d-block">Mode de Règlement</label>
                            <select class="form-select" id="mode_paiement" name="mode_paiement">
                                <option value="especes">En Espèces</option>
                                <option value="orange_money">Orange Money</option>
                                <option value="momo">MTN MoMo</option>
                                <option value="virement">Virement Bancaire</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="commentaire" class="small text-muted fw-bold text-uppercase mb-2 d-block">Description / Note Interne</label>
                            <input type="text" class="form-control" id="commentaire" name="commentaire" placeholder="Ex: Livraison différée, remise accordée...">
                        </div>
                    </div>

                    <div class="d-flex gap-3 border-top pt-4">
                        <button type="submit" class="btn btn-navy rounded-pill px-5 fw-bold">
                            Confirmer & Enregistrer
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-light rounded-pill px-4 border small fw-bold">
                            Abandonner
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section Historique Récent -->
        <div class="card card-corporate border-0 shadow-sm mt-4 overflow-hidden">
            <div class="card-header bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-navy text-uppercase ls-wide small">Dernières Opérations Enregistrées</h6>
                <a href="{{ route('transactions.index') }}" class="font-2xs text-navy fw-bold text-decoration-none">Voir tout le journal</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0" style="font-size: 0.75rem;">
                        <thead class="bg-light-subtle text-muted">
                            <tr>
                                <th class="ps-3 py-2">HEURE</th>
                                <th>CLIENT</th>
                                <th>ARTICLE</th>
                                <th>DESCRIPTION</th>
                                <th class="text-end pe-3">MONTANT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $rt)
                                <tr>
                                    <td class="ps-3 py-2 text-secondary">{{ $rt->created_at->format('H:i') }}</td>
                                    <td class="fw-bold">{{ $rt->client->nom_complet ?? 'Passant' }}</td>
                                    <td>{{ $rt->typeBouteille->nom }}</td>
                                    <td class="italic text-muted text-truncate" style="max-width: 150px;" title="{{ $rt->commentaire }}">
                                        {{ $rt->commentaire ?: '-' }}
                                    </td>
                                    <td class="text-end pe-3 fw-bold text-navy">{{ number_format($rt->montant_net, 0, ',', ' ') }} F</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">Aucune opération aujourd'hui.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-corporate border-0 shadow-sm bg-navy text-white h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-uppercase ls-wide small mb-4 opacity-75">Vérification & Guide</h6>
                
                <!-- Zone de Description Dynamique (Nature de l'opération) -->
                <div class="mb-4 p-3 rounded-4 bg-white shadow-sm border" id="nature-desc-box">
                    <small class="text-muted fw-bold text-uppercase font-2xs d-block mb-2">Nature d'Opération Sélectionnée</small>
                    <p class="text-navy small mb-0 fw-medium" id="nature-description">Veuillez choisir un type d'opération...</p>
                </div>

                <div class="info-box mb-4 p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10">
                    <p class="small mb-1 text-white text-opacity-75">Tarif unitaire applicable</p>
                    <h4 class="fw-bold mb-0 text-white" id="guide-prix">0 <small class="font-2xs">F</small></h4>
                </div>

                <div class="info-list small">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="opacity-75">Recharge Gaz</span>
                        <span class="fw-bold" id="guide-recharge">0 F</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="opacity-75">Consigne Support</span>
                        <span class="fw-bold" id="guide-consigne">0 F</span>
                    </div>
                    <hr class="opacity-10">
                    <div class="mt-4 p-3 rounded-3 bg-light-subtle text-navy border" style="background-color: #f1f5f9 !important;">
                        <p class="fw-bold mb-2 small"><i class="bi bi-info-circle me-1"></i> Rappel métier :</p>
                        <ul class="ps-3 mb-0 font-2xs">
                            <li class="mb-2"><strong>Standard :</strong> Bouteille pleine contre identique.</li>
                            <li class="mb-2"><strong>Spécifiant :</strong> Vide de marque différente.</li>
                            <li><strong>Achat :</strong> Vente sans bouteille retournée.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const descriptions = {
        'echange_simple': 'Échange Standard : Le client rend une bouteille vide de la même marque pour prendre une pleine. Il ne paie que le gaz (la recharge).',
        'echange_type': 'Échange Spécifiant : Le client rend une bouteille vide d\'une marque différente. Le prix inclut la recharge et l\'éventuel complément de consigne.',
        'achat_simple': 'Achat Simple : Le client achète une bouteille pleine neuve sans rien ramener. Il paie la bouteille (fer) et le gaz (recharge).'
    };

    function updatePrice() {
        const select = document.getElementById('id_type_bouteille');
        const option = select.options[select.selectedIndex];
        
        const typeTransaction = document.querySelector('input[name="type"]:checked').value;
        const descArea = document.getElementById('nature-description');
        descArea.textContent = descriptions[typeTransaction] || 'Veuillez sélectionner une nature d\'opération.';

        if (!option.value) return;

        const prixVente = parseFloat(option.dataset.prix) || 0;
        const consigne = parseFloat(option.dataset.consigne) || 0;
        const recharge = parseFloat(option.dataset.recharge) || 0;
        const stock = option.dataset.stock || 0;
        
        document.getElementById('stock-info').textContent = stock;
        document.getElementById('guide-prix').textContent = prixVente.toLocaleString();
        document.getElementById('guide-consigne').textContent = consigne.toLocaleString() + ' F';
        document.getElementById('guide-recharge').textContent = recharge.toLocaleString() + ' F';

        let prixSuggere = 0;

        if (typeTransaction === 'achat_simple') {
            prixSuggere = prixVente;
        } else if (typeTransaction === 'echange_simple') {
            prixSuggere = recharge;
        } else if (typeTransaction === 'echange_type') {
            prixSuggere = recharge;
            const selectAncien = document.getElementById('id_type_ancien');
            const optionAncien = selectAncien.options[selectAncien.selectedIndex];
            if (optionAncien && optionAncien.value) {
                const consigneAncien = parseFloat(optionAncien.dataset.consigne) || 0;
                const diff = consigne - consigneAncien;
                if (diff > 0) prixSuggere += diff;
            }
        }

        document.getElementById('prix_unitaire').value = Math.round(prixSuggere);
        updateTotal();
        toggleEchangeTypeFields();
    }

    function updateTotal() {
        const prix = parseFloat(document.getElementById('prix_unitaire').value) || 0;
        const qte = parseInt(document.getElementById('quantite').value) || 0;
        const total = prix * qte;
        document.getElementById('montant_total_display').value = total.toLocaleString() + ' F';
        document.getElementById('montant_total_hidden').value = total;
    }

    function toggleEchangeTypeFields() {
        const typeValue = document.querySelector('input[name="type"]:checked').value;
        const containerAncien = document.getElementById('container-ancien');
        if (typeValue === 'echange_type') {
            containerAncien.style.display = 'block';
        } else {
            containerAncien.style.display = 'none';
        }
    }

    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', updatePrice);
    });

    document.getElementById('id_type_ancien').addEventListener('change', updatePrice);
    document.addEventListener('DOMContentLoaded', updatePrice);
</script>

<style>
    .fw-extrabold { font-weight: 800; }
    .btn-check:checked + .btn-outline-navy {
        background-color: var(--navy);
        color: white;
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.2);
    }
    .form-control-lg { border-radius: 12px; }
    .form-select-lg { border-radius: 12px; }
</style>
@endsection
