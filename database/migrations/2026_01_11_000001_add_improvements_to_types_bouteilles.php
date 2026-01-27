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
        Schema::table('types_bouteilles', function (Blueprint $table) {
            // Ajouter les colonnes de prix manquantes
            $table->decimal('prix_bouteille_vide', 10, 2)->default(0)->comment('Prix de rachat bouteille vide')->after('prix_recharge');
            $table->decimal('prix_retour_vide', 10, 2)->default(0)->comment('Remboursement au retour')->after('prix_bouteille_vide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('types_bouteilles', function (Blueprint $table) {
            $table->dropColumn(['prix_bouteille_vide', 'prix_retour_vide']);
        });
    }
};
