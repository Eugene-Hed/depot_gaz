<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['numero_transaction', 'type', 'client_id', 'type_bouteille_id', 'quantite', 'quantite_vides_retournees', 'prix_unitaire', 'montant_total', 'consigne_montant', 'montant_reduction', 'montant_net', 'mode_paiement', 'statut_paiement', 'administrateur_id', 'transaction_parent_id', 'date_limite_retour', 'commentaire'];
    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'consigne_montant' => 'decimal:2',
        'montant_reduction' => 'decimal:2',
        'montant_net' => 'decimal:2',
        'date_limite_retour' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function typeBouteille(): BelongsTo
    {
        return $this->belongsTo(TypeBouteille::class);
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function transactionParent()
    {
        return $this->belongsTo(Transaction::class, 'transaction_parent_id');
    }

    public function retours()
    {
        return $this->hasMany(Transaction::class, 'transaction_parent_id');
    }
}
