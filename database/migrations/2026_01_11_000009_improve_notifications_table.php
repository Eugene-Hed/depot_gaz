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
        Schema::table('notifications', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            $table->enum('priorite', ['basse', 'normale', 'haute', 'critique'])->default('normale')->after('message');
            $table->string('entite_type')->nullable()->comment('Exemple: transaction, commande, stock')->after('priorite');
            $table->unsignedBigInteger('entite_id')->nullable()->after('entite_type');
            $table->timestamp('date_expiration')->nullable()->comment('Quand supprimer la notification')->after('statut');
            
            // Améliorer l'enum type
            $table->dropColumn('type');
            $table->enum('type', ['alerte_stock', 'retard_consigne', 'modification_prix', 'commande_livree', 'retour_impaye', 'retard_paiement'])->after('id');
            
            // Améliorer statut
            $table->dropColumn('statut');
            $table->enum('statut', ['non_lu', 'lu', 'archivee'])->default('non_lu')->after('administrateur_id');
            
            // Ajouter index
            $table->index('priorite');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['priorite']);
            $table->dropIndex(['type']);
            $table->dropColumn(['priorite', 'entite_type', 'entite_id', 'date_expiration']);
            
            $table->dropColumn('type');
            $table->enum('type', ['alerte_stock', 'retard_consigne', 'modification_prix'])->after('id');
            
            $table->dropColumn('statut');
            $table->enum('statut', ['non_lu', 'lu'])->default('non_lu')->after('administrateur_id');
        });
    }
};
