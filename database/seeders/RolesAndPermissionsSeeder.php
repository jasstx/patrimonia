<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Créer les permissions
        $permissions = [
            ['nom_permission' => 'gerer_utilisateurs', 'description' => 'Gérer les utilisateurs et leurs rôles'],
            ['nom_permission' => 'gerer_demandes', 'description' => 'Valider ou rejeter les demandes'],
            ['nom_permission' => 'voir_demandes', 'description' => 'Voir la liste des demandes'],
            ['nom_permission' => 'gerer_patrimoines', 'description' => 'Gérer les éléments patrimoniaux'],
            ['nom_permission' => 'gerer_detenteurs', 'description' => 'Gérer les détenteurs de patrimoine'],
            ['nom_permission' => 'exporter_donnees', 'description' => 'Exporter les données en PDF/Excel'],
            ['nom_permission' => 'voir_statistiques', 'description' => 'Voir les tableaux de bord statistiques'],
        ];

        foreach ($permissions as $perm) {
            Permission::create(array_merge($perm, [
                'date_cree' => Carbon::now(),
                'cree_par' => 'System',
            ]));
        }

        // Créer les rôles
        $roles = [
            [
                'nom_role' => 'Administrateur',
                'description' => 'Accès complet au système',
                'date_cree' => Carbon::now(),
                'cree_par' => 'System',
            ],
            [
                'nom_role' => 'Gestionnaire',
                'description' => 'Gère les demandes et les détenteurs',
                'date_cree' => Carbon::now(),
                'cree_par' => 'System',
            ],
            [
                'nom_role' => 'Visiteur',
                'description' => 'Peut consulter les données publiques',
                'date_cree' => Carbon::now(),
                'cree_par' => 'System',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }

        // Assigner les permissions aux rôles
        $adminRole = Role::where('nom_role', 'Administrateur')->first();
        $gestionnaireRole = Role::where('nom_role', 'Gestionnaire')->first();
        $visiteurRole = Role::where('nom_role', 'Visiteur')->first();

        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id_permission'));

        $gestionnairePermissions = Permission::whereIn('nom_permission', [
            'gerer_demandes', 'voir_demandes', 'gerer_detenteurs', 'voir_statistiques'
        ])->get();
        $gestionnaireRole->permissions()->sync($gestionnairePermissions->pluck('id_permission'));

        $visiteurPermissions = Permission::whereIn('nom_permission', [
            'voir_demandes'
        ])->get();
        $visiteurRole->permissions()->sync($visiteurPermissions->pluck('id_permission'));

        $this->command->info('Rôles et permissions créés avec succès!');
    }
}
