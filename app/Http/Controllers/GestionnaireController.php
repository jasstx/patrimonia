<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Detenteur;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class GestionnaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:gestionnaire'); // Utilise le nouveau middleware
    }

    public function dashboard()
    {
        // Statistiques simples
        $stats = [
            'demandes_attente' => Demande::where('status', 'en_attente')->count(),
            'demandes_cours' => Demande::where('status', 'en_cours')->count(),
            'demandes_validees' => Demande::where('status', 'validee')->count(),
            'demandes_rejetees' => Demande::where('status', 'rejetee')->count(),
        ];

        return view('gestionnaire.dashboard', compact('stats'));
    }

    public function demandes()
    {
        $demandes = Demande::with('demandeur')
                          ->whereIn('status', ['en_attente', 'en_cours'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        return view('gestionnaire.demandes', compact('demandes'));
    }

    // ... autres méthodes simplifiées ...
}
