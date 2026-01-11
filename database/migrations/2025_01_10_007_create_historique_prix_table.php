<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historique_prix', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_bouteille_id')->constrained('types_bouteilles')->onDelete('cascade');
            $table->decimal('ancien_prix', 10, 2);
            $table->decimal('nouveau_prix', 10, 2);
            $table->foreignId('administrateur_id')->constrained('users')->onDelete('restrict');
            $table->string('type_prix', 50); // 'vente', 'consigne', 'recharge'
            $table->timestamps();
            
            $table->index('type_bouteille_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_prix');
    }
};
