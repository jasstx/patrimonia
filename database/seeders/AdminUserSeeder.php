<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Créer l'utilisateur administrateur
        $admin = User::create([
            'name' => 'Administrateur Patrimonia',
            'email' => 'admin@patrimonia.bf',
            'password' => Hash::make('admin123'),
            'telephone' => '+226 70 00 00 00',
            'type_utilisateur' => 'admin',
            'is_active' => true,
            'cree_par' => 'System',
            'email_verified_at' => now(),
        ]);

        // Assigner le rôle administrateur
        $adminRole = Role::where('nom_role', 'Administrateur')->first();
        $admin->roles()->attach($adminRole->id_role);

        // Créer un utilisateur gestionnaire
        $gestionnaire = User::create([
            'name' => 'Gestionnaire Patrimonia',
            'email' => 'gestionnaire@patrimonia.bf',
            'password' => Hash::make('gest123'),
            'telephone' => '+226 70 00 00 01',
            'type_utilisateur' => 'gestionnaire',
            'is_active' => true,
            'cree_par' => 'System',
            'email_verified_at' => now(),
        ]);

        $gestionnaireRole = Role::where('nom_role', 'Gestionnaire')->first();
        $gestionnaire->roles()->attach($gestionnaireRole->id_role);

        $this->command->info('Utilisateurs administrateur et gestionnaire créés avec succès!');
        $this->command->info('Email admin: admin@patrimonia.bf | Mot de passe: admin123');
        $this->command->info('Email gestionnaire: gestionnaire@patrimonia.bf | Mot de passe: gest123');
    }
}
