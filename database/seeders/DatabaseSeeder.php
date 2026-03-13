<?php

namespace Database\Seeders;

use App\Models\Operateur;
use App\Models\Abonne;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Remplit la base de données avec des données de test.
     */
    public function run(): void
    {
        // Création d'opérateurs de test
        Operateur::create([
            'nom' => 'Admin Principal',
            'email' => 'admin@camwater.cm',
            'mot_de_passe' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        Operateur::create([
            'nom' => 'Operateur royn',
            'email' => 'jean@camwater.cm',
            'mot_de_passe' => Hash::make('password123'),
            'role' => 'operateur'
        ]);

        Operateur::create([
            'nom' => 'Operateur MBONDI',
            'email' => 'marie@camwater.cm',
            'mot_de_passe' => Hash::make('password123'),
            'role' => 'operateur'
        ]);

        // Création d'abonnés de test
        Abonne::create([
            'nom' => 'royn',
            'prenom' => 'mbondi',
            'telephone' => '+237690000001',
            'adresse' => 'Yaoundé, Bastos',
            "type"=> "Domestique",
            'ville'=>'yaounde',
            'numero_compteur' => 'COMP-2024-001',
            'date_abonnement' => '2024-01-15'
        ]);

        Abonne::create([
            'nom' => 'raphael',
            'prenom' => 'Mbondi',
            'telephone' => '+237690000002',
            'adresse' => 'Douala, Akwa',
            'numero_compteur' => 'COMP-2024-002',
            'date_abonnement' => '2024-01-20'
        ]);

        Abonne::create([
            'nom' => 'Mbarga',
            'prenom' => 'Joseph',
            'telephone' => '+237690000003',
            'adresse' => 'Yaoundé, Melen',
            'numero_compteur' => 'COMP-2024-003',
            'date_abonnement' => '2024-02-01'
        ]);

        Abonne::create([
            'nom' => 'Fotso',
            'prenom' => 'brandon',
            'telephone' => '+237690000004',
            'adresse' => 'Douala, Bonanjo',
            'numero_compteur' => 'COMP-2024-004',
            'date_abonnement' => '2024-02-10'
        ]);

        Abonne::create([
            'nom' => 'loic',
            'prenom' => 'André',
            'telephone' => '+237690000005',
            'adresse' => 'Bafoussam, Centre',
            'numero_compteur' => 'COMP-2024-005',
            'date_abonnement' => '2024-02-15'
        ]);
    }
}
