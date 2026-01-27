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
        Schema::table('historique_prix', function (Blueprint $table) {
            // Modifier le type_prix si nécessaire et ajouter colonnes manquantes
            if (!Schema::hasColumn('historique_prix', 'raison')) {
                $table->string('raison')->nullable()->comment('Pourquoi le changement')->after('nouveau_prix');
            }
            if (!Schema::hasColumn('historique_prix', 'date_effet')) {
                $table->date('date_effet')->nullable()->comment('Quand applicable')->after('raison');
                $table->index('date_effet');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historique_prix', function (Blueprint $table) {
            $table->dropIndex(['date_effet']);
            $table->dropColumn(['type_prix', 'raison', 'date_effet']);
        });
    }
};
