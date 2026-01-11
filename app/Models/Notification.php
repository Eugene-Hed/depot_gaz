<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['type', 'message', 'administrateur_id', 'statut'];

    public function administrateur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function marquerLue(): void
    {
        $this->update(['statut' => 'lu']);
    }
}
