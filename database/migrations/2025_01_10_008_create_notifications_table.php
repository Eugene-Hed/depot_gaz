<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['alerte_stock', 'retard_consigne', 'modification_prix']);
            $table->text('message');
            $table->foreignId('administrateur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('statut', ['non_lu', 'lu'])->default('non_lu');
            $table->timestamps();
            
            $table->index('administrateur_id');
            $table->index('statut');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
