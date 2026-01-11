@extends('layouts.app')

@section('title', 'Nouvelle transaction')

@section('content')
    <h1 class="mb-4"><i class="bi bi-plus-circle"></i> Nouvelle vente</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Type de transaction *</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="vente" {{ old('type') == 'vente' ? 'selected' : '' }}>Vente</option>
                                    <option value="recharge" {{ old('type') == 'recharge' ? 'selected' : '' }}>Recharge</option>
                                    <option value="echange" {{ old('type') == 'echange' ? 'selected' : '' }}>Échange</option>
                                    <option value="consigne" {{ old('type') == 'consigne' ? 'selected' : '' }}>Consigne</option>
                                    <option value="retour" {{ old('type') == 'retour' ? 'selected' : '' }}>Retour</option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_client" class="form-label">Client (optionnel)</label>
                                <select class="form-select @error('id_client') is-invalid @enderror" 
                                    id="id_client" name="id_client">
                                    <option value="">-- Aucun --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('id_client') == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }} ({{ $client->telephone }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_client')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_type_bouteille" class="form-label">Bouteille *</label>
                                <select class="form-select @error('id_type_bouteille') is-invalid @enderror" 
                                    id="id_type_bouteille" name="id_type_bouteille" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" 
                                            data-prix="{{ $type->prix_vente }}"
                                            data-stock="{{ $type->stock->quantite_pleine ?? 0 }}"
                                            {{ old('id_type_bouteille') == $type->id ? 'selected' : '' }}>
                                            {{ $type->marque->nom }} - {{ $type->nom }} ({{ $type->taille }}L)
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_type_bouteille')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantite" class="form-label">Quantité *</label>
                                <input type="number" class="form-control @error('quantite') is-invalid @enderror" 
                                    id="quantite" name="quantite" value="{{ old('quantite', 1) }}" min="1" required>
                                @error('quantite')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="text-muted" id="stock-info"></small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prix_unitaire" class="form-label">Prix unitaire *</label>
                                <input type="number" class="form-control @error('prix_unitaire') is-invalid @enderror" 
                                    id="prix_unitaire" name="prix_unitaire" value="{{ old('prix_unitaire', 0) }}" 
                                    step="0.01" required>
                                @error('prix_unitaire')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="montant_total" class="form-label">Montant total *</label>
                                <input type="number" class="form-control @error('montant_total') is-invalid @enderror" 
                                    id="montant_total" name="montant_total" value="{{ old('montant_total', 0) }}" 
                                    step="0.01" required>
                                @error('montant_total')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mode_paiement" class="form-label">Mode de paiement</label>
                                <select class="form-select" id="mode_paiement" name="mode_paiement">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="especes" {{ old('mode_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                                    <option value="cheque" {{ old('mode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                                    <option value="carte" {{ old('mode_paiement') == 'carte' ? 'selected' : '' }}>Carte</option>
                                    <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="commentaire" class="form-label">Commentaire</label>
                                <input type="text" class="form-control" id="commentaire" name="commentaire" 
                                    value="{{ old('commentaire') }}">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Enregistrer
                            </button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('id_type_bouteille').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const stock = selected.dataset.stock;
            document.getElementById('stock-info').textContent = stock ? `Stock: ${stock} unités` : '';
        });

        // Calculer montant total
        document.getElementById('prix_unitaire').addEventListener('input', updateTotal);
        document.getElementById('quantite').addEventListener('input', updateTotal);

        function updateTotal() {
            const prix = parseFloat(document.getElementById('prix_unitaire').value) || 0;
            const qte = parseInt(document.getElementById('quantite').value) || 0;
            document.getElementById('montant_total').value = (prix * qte).toFixed(2);
        }
    </script>
@endsection
