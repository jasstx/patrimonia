<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        $user = Auth::user();

        // Vérifier que l'utilisateur est actif
        if (!$user->is_active) {
            Auth::logout();
            return redirect('/login')->with('error', 'Votre compte a été désactivé.');
        }

        foreach ($roles as $role) {
            if ($user->type_utilisateur === strtolower($role)) {
                return $next($request);
            }
        }

        // Utilisateur authentifié mais sans le bon rôle
        return redirect('/dashboard')->with('error', 'Accès non autorisé. Vous n\'avez pas les permissions nécessaires.');
    }
}
