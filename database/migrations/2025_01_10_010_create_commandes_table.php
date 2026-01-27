<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->onDelete('restrict');
            $table->string('numero_commande', 50)->unique();
            $table->date('date_livraison_prevue')->nullable();
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut', ['en_attente', 'validee', 'livree', 'annulee'])->default('en_attente');
            $table->foreignId('administrateur_id')->constrained('users')->onDelete('restrict');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('fournisseur_id');
            $table->index('administrateur_id');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
