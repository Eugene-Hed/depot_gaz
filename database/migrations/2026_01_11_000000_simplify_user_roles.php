<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre tous les utilisateurs en tant qu'admin
        DB::table('users')->update(['role' => 'admin']);
        
        // Changer le rôle enum pour ne contenir que 'admin'
        Schema::table('users', function (Blueprint $table) {
            // Pour MariaDB/MySQL, on doit recréer la colonne
            $table->dropColumn('role');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin'])->default('admin')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'manager', 'vendeur'])->default('vendeur')->after('email');
        });
    }
};
