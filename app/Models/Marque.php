<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marque extends Model
{
    protected $table = 'marques';
    protected $fillable = ['nom', 'description', 'image'];
    protected $appends = ['image_url'];
    
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/marques/' . $this->image);
        }
        return asset('images/marque-default.png');
    }

    public function typesBouteilles(): HasMany
    {
        return $this->hasMany(TypeBouteille::class);
    }
}
