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
        Schema::table('mouvements_stocks', function (Blueprint $table) {
            // Ajouter 'casse' au type de mouvement
            $table->dropColumn('type_mouvement');
            $table->enum('type_mouvement', ['entree', 'sortie', 'ajustement', 'casse'])->after('stock_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mouvements_stocks', function (Blueprint $table) {
            $table->dropColumn('type_mouvement');
            $table->enum('type_mouvement', ['entree', 'sortie', 'ajustement'])->after('stock_id');
        });
    }
};
