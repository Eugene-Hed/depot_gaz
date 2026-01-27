<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_bouteille_id')->constrained('types_bouteilles')->onDelete('cascade');
            $table->integer('quantite_pleine')->default(0);
            $table->integer('quantite_vide')->default(0);
            $table->timestamps();
            
            $table->unique('type_bouteille_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
