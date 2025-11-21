<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;
use Carbon\Carbon;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'initiale' => 'CPNU',
                'nom_complet' => 'Les Connaissances et Pratiques liées à la nature et à l\'Univers',
                'description' => 'Connaissances traditionnelles liées à la nature, la médecine traditionnelle, l\'agriculture, etc.',
                'couleur' => '#28a745',
                'est_actif' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'initiale' => 'PSREF',
                'nom_complet' => 'Les Pratiques Sociales, les Rites et les Événements Festifs',
                'description' => 'Pratiques sociales, rites, cérémonies, événements festifs et traditions communautaires',
                'couleur' => '#dc3545',
                'est_actif' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'initiale' => 'ADS',
                'nom_complet' => 'Les Arts du Spectacle',
                'description' => 'Danses traditionnelles, musiques, chorégraphies et arts performatifs',
                'couleur' => '#007bff',
                'est_actif' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'initiale' => 'SFAT',
                'nom_complet' => 'Les Savoir-faire liés à l\'Artisanat Traditionnel',
                'description' => 'Artisanat traditionnel, techniques de fabrication, savoir-faire artisanaux',
                'couleur' => '#ffc107',
                'est_actif' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'initiale' => 'TEO',
                'nom_complet' => 'Les Traditions et expressions orales',
                'description' => 'Contes, légendes, chants panégyriques et traditions orales',
                'couleur' => '#6f42c1',
                'est_actif' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Categorie::insert($categories);

        $this->command->info('5 catégories de patrimoine créées avec succès!');
    }
}
