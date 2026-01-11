<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marque extends Model
{
    protected $table = 'marques';
    protected $fillable = ['nom', 'description'];

    public function typesBouteilles(): HasMany
    {
        return $this->hasMany(TypeBouteille::class);
    }
}
