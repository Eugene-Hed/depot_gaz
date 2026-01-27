<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_bouteilles', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('taille', 50);
            $table->foreignId('marque_id')->constrained('marques')->onDelete('cascade');
            $table->decimal('prix_vente', 10, 2);
            $table->decimal('prix_consigne', 10, 2);
            $table->decimal('prix_recharge', 10, 2)->default(0);
            $table->integer('seuil_alerte')->default(5);
            $table->timestamps();
            
            $table->unique(['nom', 'marque_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_bouteilles');
    }
};
