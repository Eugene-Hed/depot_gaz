<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('types_bouteilles', function (Blueprint $table) {
            $table->dropUnique(['nom', 'marque_id']);
            $table->dropColumn('nom');
            $table->unique(['taille', 'marque_id']);
        });
    }

    public function down(): void
    {
        Schema::table('types_bouteilles', function (Blueprint $table) {
            $table->dropUnique(['taille', 'marque_id']);
            $table->string('nom', 100)->after('id');
            $table->unique(['nom', 'marque_id']);
        });
    }
};
