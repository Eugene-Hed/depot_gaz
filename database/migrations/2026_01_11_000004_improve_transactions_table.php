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
        Schema::table('transactions', function (Blueprint $table) {
            // Ajouter les colonnes manquantes pour gérer les consignes et retours
            $table->string('numero_transaction')->unique()->after('id')->comment('Référence unique de la transaction');
            $table->integer('quantite_vides_retournees')->default(0)->after('quantite')->comment('Pour échange/retour');
            $table->decimal('consigne_montant', 10, 2)->nullable()->after('montant_total')->comment('Montant de la consigne');
            $table->decimal('montant_reduction', 10, 2)->default(0)->after('consigne_montant');
            $table->decimal('montant_net', 10, 2)->after('montant_reduction')->comment('Après réduction');
            $table->enum('statut_paiement', ['en_attente', 'payé', 'remboursé', 'partiellement_payé'])->default('payé')->after('mode_paiement');
            $table->unsignedBigInteger('transaction_parent_id')->nullable()->after('statut_paiement')->comment('Lien consigne initiale');
            $table->date('date_limite_retour')->nullable()->after('transaction_parent_id')->comment('Pour consigne/retour');
            
            // Ajouter foreign key pour transaction_parent_id
            $table->foreign('transaction_parent_id')->references('id')->on('transactions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['transaction_parent_id']);
            $table->dropColumn([
                'numero_transaction',
                'quantite_vides_retournees',
                'montant_reduction',
                'montant_net',
                'statut_paiement',
                'transaction_parent_id',
                'date_limite_retour'
            ]);
        });
    }
};
