<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marque;
use App\Models\TypeBouteille;
use App\Models\Stock;

class MarqueSeeder extends Seeder
{
    public function run(): void
    {
        $marque = Marque::create([
            'nom' => 'TotalEnergies',
            'description' => 'A la maison ou à l\'extérieur, le GPL - gaz de pétrole liquéfié, est là pour répondre à tous vos besoins énergétiques.',
        ]);

        // Types de bouteilles
        $tb1 = TypeBouteille::create([
            'nom' => 'Total 6kg',
            'taille' => '6',
            'marque_id' => $marque->id,
            'prix_vente' => 13520.00,
            'prix_consigne' => 10400.00,
            'prix_recharge' => 1000.00,
            'seuil_alerte' => 5,
        ]);

        $tb2 = TypeBouteille::create([
            'nom' => 'Total 12.5kg',
            'taille' => '12.5',
            'marque_id' => $marque->id,
            'prix_vente' => 241200.00,
            'prix_consigne' => 17700.00,
            'prix_recharge' => 0.00,
            'seuil_alerte' => 5,
        ]);

        // Stocks associés
        Stock::create([
            'type_bouteille_id' => $tb1->id,
            'quantite_pleine' => 50,
            'quantite_vide' => 0,
        ]);

        Stock::create([
            'type_bouteille_id' => $tb2->id,
            'quantite_pleine' => 30,
            'quantite_vide' => 0,
        ]);
    }
}
