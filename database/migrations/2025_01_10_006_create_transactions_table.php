<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['vente', 'echange', 'consigne', 'retour', 'recharge']);
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->foreignId('type_bouteille_id')->constrained('types_bouteilles')->onDelete('restrict');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 10, 2);
            $table->string('mode_paiement', 50)->nullable();
            $table->foreignId('administrateur_id')->constrained('users')->onDelete('restrict');
            $table->text('commentaire')->nullable();
            $table->timestamps();
            
            $table->index('client_id');
            $table->index('administrateur_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
