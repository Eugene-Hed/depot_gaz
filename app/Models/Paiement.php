<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $fillable = [
        'transaction_id',
        'client_id',
        'montant_paye',
        'mode_paiement',
        'reference_cheque',
        'reference_virement',
        'reference_carte',
        'statut',
        'administrateur_id',
        'notes',
        'date_paiement',
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'montant_paye' => 'decimal:2',
    ];

    // Relations
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function administrateur()
    {
        return $this->belongsTo(User::class, 'administrateur_id');
    }
}
