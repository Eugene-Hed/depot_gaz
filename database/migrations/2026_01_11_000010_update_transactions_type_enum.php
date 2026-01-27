<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modifier la colonne type de ENUM à VARCHAR pour supporter les nouveaux types
        Schema::table('transactions', function (Blueprint $table) {
            // Changer de ENUM à STRING (VARCHAR)
            DB::statement("ALTER TABLE transactions MODIFY COLUMN type VARCHAR(50) NOT NULL");
        });
    }

    public function down(): void
    {
        // Revenir à ENUM
        Schema::table('transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('echange_simple', 'echange_type', 'achat_simple', 'echange_differe') NOT NULL");
        });
    }
};
