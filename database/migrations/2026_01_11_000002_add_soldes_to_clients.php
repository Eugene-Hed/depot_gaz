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
        Schema::table('clients', function (Blueprint $table) {
            $table->decimal('solde_credit', 10, 2)->default(0)->comment('Montant à rembourser au client')->after('points_fidelite');
            $table->decimal('solde_dette', 10, 2)->default(0)->comment('Montant à payer par le client')->after('solde_credit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['solde_credit', 'solde_dette']);
        });
    }
};
