<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Créer la table detail_commandes
        Schema::create('detail_commandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commande_id');
            $table->unsignedBigInteger('type_bouteille_id');
            
            $table->integer('quantite_commandee');
            $table->integer('quantite_livree')->default(0);
            // quantite_restante sera calculée: quantite_commandee - quantite_livree
            
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_ligne', 10, 2);
            
            $table->enum('statut_ligne', ['en_attente', 'livree_partielle', 'livree', 'annulee'])->default('en_attente');
            
            $table->timestamps();
            
            // Index et contraintes
            $table->index('commande_id');
            $table->index('type_bouteille_id');
            
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('type_bouteille_id')->references('id')->on('types_bouteilles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_commandes');
    }
};
