<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('types_bouteilles', function (Blueprint $table) {
            $table->enum('statut', ['actif', 'inactif'])->default('actif')->after('seuil_alerte');
        });
    }

    public function down(): void
    {
        Schema::table('types_bouteilles', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }
};
