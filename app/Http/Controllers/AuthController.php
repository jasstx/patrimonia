<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'is_active' => true], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // Vérifier si l'email existe mais est inactif
        $user = User::where('email', $credentials['email'])->first();
        if ($user && !$user->is_active) {
            return back()->withErrors(['email' => 'Votre compte a été désactivé. Contactez l\'administrateur.'])->onlyInput('email');
        }

        return back()->withErrors(['email' => 'Identifiants invalides'])->onlyInput('email');
    }

    /**
     * Affiche le formulaire d'inscription pour les demandeurs
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Traite l'inscription d'un nouveau demandeur
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'telephone' => 'nullable|string|max:30',
                'password' => ['required', 'confirmed', Password::defaults()],
                'accept_terms' => 'required|accepted',
            ], [
                'email.unique' => 'Cet email est déjà utilisé. Veuillez vous connecter ou utiliser un autre email.',
                'accept_terms.required' => 'Vous devez accepter les conditions d\'utilisation.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            ]);

            // Créer l'utilisateur (type visiteur par défaut)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'] ?? null,
                'password' => Hash::make($validated['password']),
                'type_utilisateur' => 'visiteur', // Les demandeurs sont considérés comme des visiteurs
                'is_active' => true,
            ]);

            // Assigner le rôle "Visiteur" par défaut (ils pourront faire des demandes publiques)
            $visiteurRole = Role::where('nom_role', 'Visiteur')->first();
            if ($visiteurRole) {
                $user->roles()->attach($visiteurRole->id_role);
            } else {
                \Log::warning('Le rôle "Visiteur" n\'existe pas en base de données. L\'utilisateur a été créé mais sans rôle.');
            }

            // Connecter automatiquement l'utilisateur et régénérer la session
            Auth::loginUsingId($user->id);
            $request->session()->regenerate();

            return redirect()->route('demande.create')
                ->with('success', 'Votre compte a été créé avec succès ! Vous pouvez maintenant créer une demande.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'inscription: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.']);
        }
    }
}













