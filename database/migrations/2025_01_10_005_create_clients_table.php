<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->string('telephone', 15);
            $table->string('email', 255)->nullable()->unique();
            $table->text('adresse')->nullable();
            $table->integer('points_fidelite')->default(0);
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
            
            $table->index('telephone');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
