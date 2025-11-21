<?php

namespace App\Http\Controllers;

use App\Models\Detenteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DetenteurController extends Controller
{
    /**
     * Affiche la liste des détenteurs
     */
    public function index()
    {
        // Charger les détenteurs avec leurs relations
        $detenteurs = Detenteur::with([
            'demandeur',
            'patrimoines' => function($query) {
                $query->select('patrimoines.*');
            }
        ])
        ->verifies()
        ->orderBy('created_at', 'desc')
        ->get()
        ->each(function($detenteur) {
            // S'assurer que l'URL de la photo est correctement formatée
            $detenteur->photo_url = $detenteur->photo_url;
        });

        return view('detenteurs.index', compact('detenteurs'));
    }

    /**
     * Affiche les détails d'un détenteur
     */
    public function show($id_detenteur)
    {
        try {
            $detenteur = Detenteur::with([
                'demandeur',
                'patrimoines',
                'verificateur'
            ])->findOrFail($id_detenteur);

            // Vérifier si le fichier photo existe
            if ($detenteur->photo) {
                $photoPath = storage_path('app/public/' . $detenteur->photo);
                if (!file_exists($photoPath)) {
                    // Utilisation de la syntaxe complète pour éviter les problèmes de résolution
                    \Illuminate\Support\Facades\Log::warning("Photo non trouvée: " . $detenteur->photo);
                }
            }

            return view('detenteurs.show', compact('detenteur'));

        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            \Illuminate\Support\Facades\Log::error('Erreur lors du chargement du détenteur: ' . $e->getMessage());

            // Rediriger avec un message d'erreur
            return redirect()->route('detenteurs.index')
                ->with('error', 'Erreur lors du chargement des données du détenteur.');
        }
    }

    /**
     * API endpoint pour récupérer les données d'un détenteur (pour le popup)
     */
    public function apiShow($id_detenteur)
    {
        try {
            $detenteur = Detenteur::with([
                'demandeur',
                'patrimoines.categorie',
                'verificateur',
                'demandeur.demandes'
            ])->findOrFail($id_detenteur);

            // Ajouter l'URL complète de la photo
            if ($detenteur->photo) {
                $detenteur->photo_url = asset('storage/' . $detenteur->photo);
            }

            return response()->json($detenteur);

        } catch (\Exception $e) {
            Log::error('Erreur API détenteur: ' . $e->getMessage());
            return response()->json(['error' => 'Détenteur non trouvé'], 404);
        }
    }
}
