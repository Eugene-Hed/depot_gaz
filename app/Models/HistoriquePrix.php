<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriquePrix extends Model
{
    protected $table = 'historique_prix';
    protected $fillable = ['type_bouteille_id', 'ancien_prix', 'nouveau_prix', 'administrateur_id', 'type_prix'];
    protected $casts = [
        'ancien_prix' => 'decimal:2',
        'nouveau_prix' => 'decimal:2',
    ];

    public function typeBouteille(): BelongsTo
    {
        return $this->belongsTo(TypeBouteille::class);
    }

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
