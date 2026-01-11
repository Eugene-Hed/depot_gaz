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
        Schema::table('commandes', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            $table->timestamp('date_commande')->useCurrent()->after('numero_commande')->comment('Date de création');
            $table->timestamp('date_livraison_effective')->nullable()->after('date_livraison_prevue')->comment('Date réelle de livraison');
            $table->decimal('montant_ht', 10, 2)->after('date_livraison_effective')->comment('Hors taxes');
            $table->decimal('montant_taxes', 10, 2)->default(0)->after('montant_ht');
            $table->decimal('cout_transport', 10, 2)->default(0)->after('montant_taxes');
            
            // Modifier montant_total pour qu'il soit mieux documenté
            $table->comment('montant_total = montant_ht + montant_taxes + cout_transport');
            
            // Améliorer l'enum statut
            $table->dropColumn('statut');
            $table->enum('statut', ['en_attente', 'validee', 'livree_partielle', 'livree', 'annulee'])->default('en_attente')->after('cout_transport');
            
            // Ajouter index pour les dates
            $table->index('date_commande');
            $table->index('date_livraison_effective');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropIndex(['date_commande']);
            $table->dropIndex(['date_livraison_effective']);
            $table->dropColumn([
                'date_commande',
                'date_livraison_effective',
                'montant_ht',
                'montant_taxes',
                'cout_transport'
            ]);
            $table->dropColumn('statut');
            $table->enum('statut', ['en_attente', 'validee', 'livree', 'annulee'])->default('en_attente')->after('date_livraison_prevue');
        });
    }
};
