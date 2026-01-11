<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeBouteille extends Model
{
    protected $table = 'types_bouteilles';
    protected $fillable = ['nom', 'taille', 'marque_id', 'prix_vente', 'prix_consigne', 'prix_recharge', 'prix_bouteille_vide', 'prix_retour_vide', 'seuil_alerte'];
    protected $casts = [
        'prix_vente' => 'decimal:2',
        'prix_consigne' => 'decimal:2',
        'prix_recharge' => 'decimal:2',
        'prix_bouteille_vide' => 'decimal:2',
        'prix_retour_vide' => 'decimal:2',
    ];

    public function marque(): BelongsTo
    {
        return $this->belongsTo(Marque::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    public function mouvements(): HasMany
    {
        return $this->hasManyThrough(MouvementStock::class, Stock::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function detailCommandes()
    {
        return $this->hasMany(DetailCommande::class);
    }
}
