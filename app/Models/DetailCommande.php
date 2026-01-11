<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailCommande extends Model
{
    protected $table = 'detail_commandes';

    protected $fillable = [
        'commande_id',
        'type_bouteille_id',
        'quantite_commandee',
        'quantite_livree',
        'prix_unitaire',
        'montant_ligne',
        'statut_ligne',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'montant_ligne' => 'decimal:2',
    ];

    // Relations
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function typeBouteille()
    {
        return $this->belongsTo(TypeBouteille::class);
    }

    // Accesseur pour calculer la quantité restante
    public function getQuantiteRestanteAttribute()
    {
        return $this->quantite_commandee - $this->quantite_livree;
    }
}
