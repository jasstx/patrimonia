<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Créer le super administrateur
        $admin = User::firstOrCreate(
            ['email' => 'admin@patrimonia.bf'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'telephone' => '+226 70 00 00 00',
                'type_utilisateur' => 'admin',
                'is_active' => true,
                'cree_par' => 'System',
                'email_verified_at' => now(),
            ]
        );

        // S'assurer que le rôle admin existe
        $adminRole = Role::firstOrCreate(
            ['nom_role' => 'Administrateur'],
            [
                'description' => 'Accès complet à toutes les fonctionnalités',
                'est_actif' => true
            ]
        );

        // Attribuer le rôle admin
        $admin->roles()->sync([$adminRole->id_role]);

        $this->command->info('Super administrateur créé avec succès !');
        $this->command->info('Email: admin@patrimonia.bf');
        $this->command->info('Mot de passe: admin123');
    }
}
