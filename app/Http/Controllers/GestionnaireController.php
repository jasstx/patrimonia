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
        if (!app()->environment('local')) {
            $this->middleware('auth');
            $this->middleware('role:gestionnaire');
        }
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

       // dd($demandes);

        return view('gestionnaire.demandes', compact('demandes'));
    }

    public function show($id)
    {
        $demande = Demande::with(['demandeur', 'patrimoines', 'piecesJointes'])->findOrFail($id);
        return view('gestionnaire.show', compact('demande'));
    }

    public function valider($id, Request $request)
    {
        $demande = Demande::with(['demandeur', 'patrimoines'])->findOrFail($id);
        $demande->valider($request->user());
        return back()->with('success', 'Demande validée et détenteur créé automatiquement.');
    }

    public function rejeter($id, Request $request)
    {
        $request->validate(['motif' => 'required|string|min:3']);
        $demande = Demande::findOrFail($id);
        $demande->rejeter($request->user(), $request->motif);
        return back()->with('success', 'Demande rejetée.');
    }

    /**
     * Afficher la liste des détenteurs validés
     */
    public function detenteursValides(Request $request)
    {
        $query = Detenteur::with(['demandeur', 'patrimoines', 'verificateur'])
            ->where('est_verifie', true)
            ->orderBy('date_verification', 'desc');

        // Filtre par recherche
        if ($request->filled('q')) {
            $term = '%' . $request->string('q') . '%';
            $query->whereHas('demandeur', function ($q) use ($term) {
                $q->where('nom', 'like', $term)
                  ->orWhere('prenom', 'like', $term)
                  ->orWhere('telephone', 'like', $term)
                  ->orWhere('nom_structure', 'like', $term);
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type_detenteur', $request->string('type'));
        }

        $detenteurs = $query->paginate(20)->withQueryString();

        return view('gestionnaire.detenteurs-valides', compact('detenteurs'));
    }

    /**
     * Afficher la liste des demandes rejetées (détenteurs refusés)
     */
    public function detenteursRefuses(Request $request)
    {
        $query = Demande::with(['demandeur', 'patrimoines', 'rejeteur'])
            ->where('status', 'rejetee')
            ->orderBy('rejetee_le', 'desc');

        // Filtre par recherche
        if ($request->filled('q')) {
            $term = '%' . $request->string('q') . '%';
            $query->whereHas('demandeur', function ($q) use ($term) {
                $q->where('nom', 'like', $term)
                  ->orWhere('prenom', 'like', $term)
                  ->orWhere('telephone', 'like', $term)
                  ->orWhere('nom_structure', 'like', $term);
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->whereHas('demandeur', function ($q) use ($request) {
                $q->where('type_detenteur', $request->string('type'));
            });
        }

        $demandes = $query->paginate(20)->withQueryString();

        return view('gestionnaire.detenteurs-refuses', compact('demandes'));
    }
}
