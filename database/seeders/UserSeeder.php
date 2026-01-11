<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'admin principal
        User::firstOrCreate(
            ['email' => 'simohedric2023@gmail.com'],
            [
                'username' => 'admin',
                'nom_complet' => 'TAMBO SIMO Hedric',
                'email' => 'simohedric2023@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'statut' => 'actif',
            ]
        );

        // Créer un admin de test
        User::firstOrCreate(
            ['email' => 'admin@depotgaz.com'],
            [
                'username' => 'testadmin',
                'nom_complet' => 'Admin Test',
                'email' => 'admin@depotgaz.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'statut' => 'actif',
            ]
        );
    }
}

