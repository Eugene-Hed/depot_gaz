<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commande extends Model
{
    protected $table = 'commandes';
    protected $fillable = ['fournisseur_id', 'numero_commande', 'date_commande', 'date_livraison_prevue', 'date_livraison_effective', 'montant_total', 'montant_ht', 'montant_taxes', 'cout_transport', 'statut', 'administrateur_id', 'notes'];
    protected $casts = [
        'montant_total' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'montant_taxes' => 'decimal:2',
        'cout_transport' => 'decimal:2',
        'date_commande' => 'date',
        'date_livraison_prevue' => 'date',
        'date_livraison_effective' => 'date',
    ];

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailCommande::class);
    }
}
