<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->string('code_fournisseur', 50)->unique();
            $table->string('email', 255)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->string('pays', 100)->nullable();
            $table->string('contact_nom', 255)->nullable();
            $table->string('contact_fonction', 100)->nullable();
            $table->string('conditions_paiement', 50)->nullable();
            $table->integer('delai_livraison')->nullable();
            $table->text('notes')->nullable();
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
