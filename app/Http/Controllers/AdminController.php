<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Demande;
use App\Models\Categorie;
use App\Models\Detenteur;
use App\Models\Patrimoine;
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
    public function utilisateurs()
    {
        $utilisateurs = User::with('roles')->get();
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
        $patrimoines = Patrimoine::with('categorie', 'detenteurs')->get();
        $categories = \App\Models\Categorie::all();

        return view('admin.patrimoines', compact('patrimoines', 'categories'));
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
}
