<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stocks';
    protected $fillable = ['stock_id', 'type_mouvement', 'quantite_pleine', 'quantite_vide', 'commentaire', 'motif', 'administrateur_id'];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getQuantiteTotalAttribute(): int
    {
        return $this->quantite_pleine + $this->quantite_vide;
    }
}
