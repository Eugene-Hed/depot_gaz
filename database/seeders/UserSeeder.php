<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'nom_complet' => 'TAMBO SIMO Hedric',
            'email' => 'simohedric2023@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'statut' => 'actif',
        ]);

        User::create([
            'username' => 'manager',
            'nom_complet' => 'Manager Test',
            'email' => 'manager@depotgaz.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'statut' => 'actif',
        ]);

        User::create([
            'username' => 'vendeur',
            'nom_complet' => 'Vendeur Test',
            'email' => 'vendeur@depotgaz.com',
            'password' => Hash::make('password'),
            'role' => 'vendeur',
            'statut' => 'actif',
        ]);
    }
}
