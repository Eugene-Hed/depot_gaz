<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
            $table->enum('type_mouvement', ['entree', 'sortie', 'ajustement']);
            $table->integer('quantite_pleine');
            $table->integer('quantite_vide');
            $table->text('commentaire')->nullable();
            $table->string('motif', 50)->nullable();
            $table->foreignId('administrateur_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            $table->index('stock_id');
            $table->index('administrateur_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stocks');
    }
};
