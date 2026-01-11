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
        // Créer la table paiements
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('client_id')->nullable();
            
            $table->decimal('montant_paye', 10, 2);
            $table->string('mode_paiement'); // especes, cheque, carte, virement
            
            // Détails selon mode de paiement
            $table->string('reference_cheque')->nullable()->comment('Numéro du chèque');
            $table->string('reference_virement')->nullable()->comment('Référence virement');
            $table->string('reference_carte')->nullable()->comment('Derniers chiffres carte');
            
            $table->enum('statut', ['en_attente', 'confirmé', 'failed', 'annulé'])->default('confirmé');
            $table->unsignedBigInteger('administrateur_id');
            $table->text('notes')->nullable();
            
            $table->timestamp('date_paiement')->useCurrent();
            $table->timestamps();
            
            // Index et contraintes
            $table->index('transaction_id');
            $table->index('client_id');
            $table->index('date_paiement');
            $table->index('administrateur_id');
            
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $table->foreign('administrateur_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
