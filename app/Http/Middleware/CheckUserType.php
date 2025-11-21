<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        // 1. Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        $user = Auth::user();

        // 2. Vérifier si le type d'utilisateur correspond
        foreach ($types as $type) {
            if ($user->type_utilisateur === $type) {
                return $next($request);
            }
        }

        // 3. Si aucun type ne correspond
        $typesRequises = implode(', ', $types);

        abort(403, "Accès refusé. Type d'utilisateur requis: {$typesRequises}. Votre type: {$user->type_utilisateur}");
    }
}
