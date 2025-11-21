<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Demande;
use App\Models\Categorie;
use App\Models\Detenteur;
use App\Models\Patrimoine;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Tableau de bord administrateur
     */
    public function dashboard()
    {
        $stats = [
            'total_utilisateurs' => User::count(),
            'total_demandes' => Demande::count(),
            'demandes_attente' => Demande::enAttente()->count(),
            'demandes_validees' => Demande::validees()->count(),
            'total_patrimoines' => Patrimoine::count(),
            'total_detenteurs' => Detenteur::count(),
            'detenteurs_verifies' => Detenteur::verifies()->count(),
        ];

        $demandesRecentes = Demande::with('demandeur')->recent()->limit(10)->get();
        $utilisateursRecents = User::recent()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'demandesRecentes', 'utilisateursRecents'));
    }

    /**
     * Gestion des utilisateurs
     */
    public function utilisateurs(Request $request)
    {
        $utilisateurs = User::with('roles')
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('type_utilisateur', $request->string('type'));
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $q->where(function ($qq) use ($term) {
                    $qq->where('name', 'like', $term)
                       ->orWhere('email', 'like', $term)
                       ->orWhere('telephone', 'like', $term);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $roles = Role::all();

        return view('admin.utilisateurs', compact('utilisateurs', 'roles'));
    }

    /**
     * Attribuer un rôle à un utilisateur
     */
    public function attribuerRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id_role'
        ]);

        $user->roles()->sync([$request->role_id]);

        return back()->with('success', 'Rôle attribué avec succès');
    }

    /**
     * Gestion des patrimoines
     */
    public function patrimoines()
    {
        $patrimoines = Patrimoine::with('categorie')
            ->withCount('detenteurs')
            ->orderBy('domaine')
            ->orderBy('numero_element')
            ->paginate(20);

        // Totaux par domaine (tous en base, pas seulement la page courante)
        $parDomaine = Patrimoine::select('domaine')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('domaine')
            ->pluck('total', 'domaine');

        // Détenteurs par domaine (distincts)
        $detenteursParDomaine = \DB::table('detenteur_patrimoine as dp')
            ->join('patrimoines as p', 'p.id_element', '=', 'dp.id_patrimoine')
            ->select('p.domaine')
            ->selectRaw('COUNT(DISTINCT dp.id_detenteur) as total')
            ->groupBy('p.domaine')
            ->pluck('total', 'p.domaine');

        return view('admin.patrimoines', compact('patrimoines', 'parDomaine', 'detenteursParDomaine'));
    }

    /**
     * Page Statistiques globales
     */
    public function statistiques()
    {
        // KPIs Demandes
        $kpiDemandes = [
            'total' => Demande::count(),
            'en_attente' => Demande::enAttente()->count(),
            'en_cours' => Demande::enCours()->count(),
            'validees' => Demande::validees()->count(),
            'rejetees' => Demande::rejetees()->count(),
        ];

        // Détenteurs
        $kpiDetenteurs = [
            'total' => Detenteur::count(),
            'verifies' => Detenteur::verifies()->count(),
        ];

        // Répartition par domaine
        $domaines = ['CPNU','PSREF','ADS','SFAT','TEO'];
        $elementsParDomaine = Patrimoine::select('domaine')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('domaine')
            ->pluck('total', 'domaine');
        $detenteursParDomaine = \DB::table('detenteur_patrimoine as dp')
            ->join('patrimoines as p', 'p.id_element', '=', 'dp.id_patrimoine')
            ->select('p.domaine')
            ->selectRaw('COUNT(DISTINCT dp.id_detenteur) as total')
            ->groupBy('p.domaine')
            ->pluck('total', 'p.domaine');

        return view('admin.statistiques', compact(
            'kpiDemandes', 'kpiDetenteurs', 'domaines', 'elementsParDomaine', 'detenteursParDomaine'
        ));
    }

    /**
     * Gestion des rôles
     */
    public function roles()
    {
        $roles = Role::withCount('users')->orderBy('nom_role')->paginate(20);
        return view('admin.roles', compact('roles'));
    }

    /**
     * Créer un nouveau rôle
     */
    public function creerRole(Request $request)
    {
        $request->validate([
            'nom_role' => 'required|string|max:255|unique:roles,nom_role',
            'description' => 'nullable|string'
        ]);

        Role::create([
            'nom_role' => $request->nom_role,
            'description' => $request->description
        ]);

        return back()->with('success', 'Rôle créé avec succès');
    }

    /**
     * Supprimer un rôle
     */
    public function supprimerRole(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un rôle assigné à des utilisateurs');
        }

        $role->delete();
        return back()->with('success', 'Rôle supprimé avec succès');
    }

    /**
     * Gestion des catégories
     */
    public function categories()
    {
        $categories = Categorie::withCount('patrimoines')->orderBy('initiale')->paginate(20);
        return view('admin.categories', compact('categories'));
    }

    /**
     * Créer une nouvelle catégorie
     */
    public function creerCategorie(Request $request)
    {
        $request->validate([
            'initiale' => 'required|string|max:10|unique:categories,initiale',
            'nom_complet' => 'required|string|max:255',
            'description' => 'nullable|string',
            'couleur' => 'nullable|string|max:7'
        ]);

        Categorie::create([
            'initiale' => strtoupper($request->initiale),
            'nom_complet' => $request->nom_complet,
            'description' => $request->description,
            'couleur' => $request->couleur ?: '#3498db'
        ]);

        return back()->with('success', 'Catégorie créée avec succès');
    }

    /**
     * Supprimer une catégorie
     */
    public function supprimerCategorie(Categorie $categorie)
    {
        if ($categorie->patrimoines()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une catégorie contenant des patrimoines');
        }

        $categorie->delete();
        return back()->with('success', 'Catégorie supprimée avec succès');
    }

    /**
     * Créer un patrimoine
     */
    public function creerPatrimoine(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required',
            'domaine' => 'required|in:CPNU,PSREF,ADS,SFAT,TEO',
            'numero_element' => 'required|integer',
            'id_categorie' => 'required|exists:categories,id_categorie',
            'description' => 'required',
            'localisation' => 'required',
            'region' => 'required',
        ]);

        Patrimoine::create($validated);

        return back()->with('success', 'Patrimoine créé avec succès');
    }

    // PERMISSIONS
    public function permissions()
    {
        $permissions = Permission::withCount('roles')->orderBy('nom_permission')->paginate(20);
        $roles = Role::with('permissions')->orderBy('nom_role')->get();
        return view('admin.permissions', compact('permissions', 'roles'));
    }

    public function creerPermission(Request $request)
    {
        $request->validate([
            'nom_permission' => 'required|string|max:255|unique:permissions,nom_permission',
            'description' => 'nullable|string',
        ]);

        Permission::create([
            'nom_permission' => $request->nom_permission,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Permission créée avec succès');
    }

    public function supprimerPermission(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une permission déjà assignée');
        }
        $permission->delete();
        return back()->with('success', 'Permission supprimée');
    }

    public function assignerPermission(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id_role',
            'permission_id' => 'required|exists:permissions,id_permission',
        ]);
        $role = Role::findOrFail($request->role_id);
        $role->permissions()->syncWithoutDetaching([$request->permission_id]);
        return back()->with('success', 'Permission assignée au rôle');
    }

    public function retirerPermission(Role $role, Permission $permission)
    {
        $role->permissions()->detach($permission->id_permission);
        return back()->with('success', 'Permission retirée du rôle');
    }

    // UTILISATEURS: reset mdp + export CSV
    public function reinitialiserMotDePasse(User $user)
    {
        $nouveau = str()->random(10);
        $user->update(['password' => Hash::make($nouveau)]);
        return back()->with('success', 'Mot de passe réinitialisé: ' . $nouveau);
    }

    public function exportUtilisateurs(Request $request)
    {
        $users = User::query()
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('type_utilisateur', $request->string('type'));
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $q->where(function ($qq) use ($term) {
                    $qq->where('name', 'like', $term)
                       ->orWhere('email', 'like', $term)
                       ->orWhere('telephone', 'like', $term);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get(['name','email','telephone','type_utilisateur','is_active','created_at']);

        $filename = 'utilisateurs_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($users) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Nom', 'Email', 'Téléphone', 'Type', 'Actif', 'Créé le']);
            foreach ($users as $u) {
                fputcsv($out, [
                    $u->name,
                    $u->email,
                    $u->telephone,
                    $u->type_utilisateur,
                    $u->is_active ? 'oui' : 'non',
                    $u->created_at,
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Créer un utilisateur (profil)
     */
    public function creerUtilisateur(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:30',
            'type_utilisateur' => 'required|in:admin,gestionnaire,visiteur',
        ]);

        $motDePasseTemporaire = str()->random(10);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'type_utilisateur' => $validated['type_utilisateur'],
            'is_active' => true,
            'password' => Hash::make($motDePasseTemporaire),
        ]);

        return back()->with('success', 'Utilisateur créé. Mot de passe temporaire: ' . $motDePasseTemporaire);
    }

    /**
     * Supprimer un utilisateur
     */
    public function supprimerUtilisateur(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

    /**
     * Activer un utilisateur
     */
    public function activerUtilisateur(User $user)
    {
        $user->update(['is_active' => true]);
        return back()->with('success', 'Utilisateur activé.');
    }

    /**
     * Désactiver un utilisateur
     */
    public function desactiverUtilisateur(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update(['is_active' => false]);
        return back()->with('success', 'Utilisateur désactivé.');
    }

    /**
     * Mettre à jour le type d'utilisateur
     */
    public function mettreAJourType(Request $request, User $user)
    {
        $request->validate([
            'type_utilisateur' => 'required|in:admin,gestionnaire,visiteur',
        ]);

        $user->update(['type_utilisateur' => $request->type_utilisateur]);
        return back()->with('success', 'Type d\'utilisateur mis à jour.');
    }
}
