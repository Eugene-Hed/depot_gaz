<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $table = 'stocks';
    protected $fillable = ['type_bouteille_id', 'quantite_pleine', 'quantite_vide'];

    public function typeBouteille(): BelongsTo
    {
        return $this->belongsTo(TypeBouteille::class);
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(MouvementStock::class);
    }

    public function estEnRupture(): bool
    {
        return $this->quantite_pleine < $this->typeBouteille->seuil_alerte;
    }

    public function getQuantiteDisponibleAttribute(): int
    {
        return $this->quantite_pleine + $this->quantite_vide;
    }
}
