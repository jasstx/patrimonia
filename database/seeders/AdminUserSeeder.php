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
        // Créer l'utilisateur administrateur initial (idempotent)
        $admin = User::firstOrCreate(
            ['email' => 'jassemalj08@gmail.com'],
            [
                'name' => 'Traore Alane',
                'password' => Hash::make('aljassem0811'),
                'telephone' => '+226 00 00 00 00',
                'type_utilisateur' => 'admin',
                'is_active' => true,
                'cree_par' => 'System',
                'email_verified_at' => now(),
            ]
        );

        // Assigner le rôle administrateur
        $adminRole = Role::where('nom_role', 'Administrateur')->first();
        if ($adminRole) {
            $admin->roles()->syncWithoutDetaching([$adminRole->id_role]);
        }

        // Créer un utilisateur gestionnaire (idempotent)
        $gestionnaire = User::firstOrCreate(
            ['email' => 'gestionnaire@patrimonia.bf'],
            [
                'name' => 'Gestionnaire Patrimonia',
                'password' => Hash::make('gest123'),
                'telephone' => '+226 70 00 00 01',
                'type_utilisateur' => 'gestionnaire',
                'is_active' => true,
                'cree_par' => 'System',
                'email_verified_at' => now(),
            ]
        );

        $gestionnaireRole = Role::where('nom_role', 'Gestionnaire')->first();
        if ($gestionnaireRole) {
            $gestionnaire->roles()->syncWithoutDetaching([$gestionnaireRole->id_role]);
        }

        $this->command->info('Utilisateurs administrateur et gestionnaire créés avec succès!');
        $this->command->info('Email admin: jassemalj08@gmail.com | Mot de passe: aljassem0811');
        $this->command->info('Email gestionnaire: gestionnaire@patrimonia.bf | Mot de passe: gest123');
    }
}
