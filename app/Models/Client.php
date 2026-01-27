<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';
    protected $fillable = ['nom', 'telephone', 'email', 'adresse', 'points_fidelite', 'solde_credit', 'solde_dette', 'statut'];
    protected $casts = [
        'solde_credit' => 'decimal:2',
        'solde_dette' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function ajouterPoints(int $points): void
    {
        $this->increment('points_fidelite', $points);
    }

    public function deduirePoints(int $points): bool
    {
        if ($this->points_fidelite >= $points) {
            $this->decrement('points_fidelite', $points);
            return true;
        }
        return false;
    }
}
