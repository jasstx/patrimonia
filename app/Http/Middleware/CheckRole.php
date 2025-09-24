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
            return redirect('/login');
        }

        $user = Auth::user();

        foreach ($roles as $role) {
            if ($user->type_utilisateur === strtolower($role)) {
                return $next($request);
            }
        }

        return redirect('/dashboard')->with('error', 'Accès non autorisé.');
    }
}
