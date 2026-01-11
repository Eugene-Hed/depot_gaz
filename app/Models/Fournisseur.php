<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fournisseur extends Model
{
    protected $table = 'fournisseurs';
    protected $fillable = ['nom', 'code_fournisseur', 'email', 'telephone', 'adresse', 'ville', 'code_postal', 'pays', 'contact_nom', 'contact_fonction', 'conditions_paiement', 'delai_livraison', 'notes', 'statut'];

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }
}
