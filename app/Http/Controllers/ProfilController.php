<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\Demandeur;
use App\Models\Patrimoine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche la page "Mon Profil" avec les demandes de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();

        // Récupérer TOUS les demandeurs de l'utilisateur (il peut en avoir plusieurs)
        $demandeurs = Demandeur::where('user_id', $user->id)->get();

        \Log::info('=== PROFIL UTILISATEUR ===');
        \Log::info('User:', ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]);
        \Log::info('Demandeurs trouvés:', ['count' => $demandeurs->count(), 'ids' => $demandeurs->pluck('id_demandeur')->toArray()]);

        // Récupérer les IDs de tous les demandeurs
        $demandeurIds = $demandeurs->pluck('id_demandeur')->toArray();

        // Initialiser les variables
        $demandes = collect();
        $nombreDemandes = 0;
        $demandesEnAttente = 0;
        $demandesValidees = 0;
        $demandesRejetees = 0;

        if (!empty($demandeurIds)) {
            // Récupérer toutes les demandes de tous les demandeurs avec leurs relations
            $demandes = Demande::with(['patrimoines', 'piecesJointes', 'demandeur'])
                ->whereIn('id_demandeur', $demandeurIds)
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('Demandes trouvées:', ['count' => $demandes->count()]);

            // Statistiques
            $nombreDemandes = $demandes->count();
            $demandesEnAttente = $demandes->where('status', 'en_attente')->count();
            $demandesValidees = $demandes->where('status', 'validee')->count();
            $demandesRejetees = $demandes->where('status', 'rejetee')->count();
        } else {
            \Log::info('Aucun demandeur trouvé pour cet utilisateur');
        }

        // Pour la compatibilité avec la vue, on utilise le premier demandeur
        $demandeur = $demandeurs->first();

        return view('profil.index', compact('user', 'demandeur', 'demandes', 'nombreDemandes', 'demandesEnAttente', 'demandesValidees', 'demandesRejetees'));
    }

    /**
     * Supprime une demande (seulement si en attente)
     */
    public function destroy($id)
    {
        $user = Auth::user();

        // Trouver le demandeur associé à l'utilisateur via la relation
        $demandeur = $user->demandeur;

        if (!$demandeur) {
            return back()->with('error', 'Profil demandeur introuvable.');
        }

        // Récupérer la demande
        $demande = Demande::findOrFail($id);

        // Vérifier que la demande appartient au demandeur
        if ($demande->id_demandeur !== $demandeur->id_demandeur) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à supprimer cette demande.');
        }

        // Vérifier que la demande est en attente
        if ($demande->status !== 'en_attente') {
            return back()->with('error', 'Vous ne pouvez supprimer que les demandes en attente.');
        }

        // Supprimer la demande (les relations seront supprimées automatiquement grâce à onDelete('cascade'))
        $demande->delete();

        return back()->with('success', 'La demande a été supprimée avec succès.');
    }

    /**
     * Afficher le formulaire d'édition d'une demande
     */
    public function edit($id)
    {
        $user = Auth::user();
        $demandeur = $user->demandeur;

        if (!$demandeur) {
            return redirect()->route('profil.index')->with('error', 'Profil demandeur introuvable.');
        }

        // Récupérer la demande
        $demande = Demande::with(['patrimoines', 'demandeur'])->findOrFail($id);

        // Vérifier que la demande appartient au demandeur
        if ($demande->id_demandeur !== $demandeur->id_demandeur) {
            return redirect()->route('profil.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette demande.');
        }

        // Vérifier que la demande est en attente
        if ($demande->status !== 'en_attente') {
            return redirect()->route('profil.index')->with('error', 'Vous ne pouvez modifier que les demandes en attente.');
        }

        // Récupérer tous les patrimoines par domaine
        $domaines = [
            'Les Connaissances et Pratiques liées à la nature et à l\'Univers (CPNU)' =>
                Patrimoine::where('domaine', 'CPNU')->orderBy('numero_element')->get(),

            'Les Pratiques Sociales, les Rites et les Événements Festifs (PSREF)' =>
                Patrimoine::where('domaine', 'PSREF')->orderBy('numero_element')->get(),

            'Les Arts du Spectacle (ADS)' =>
                Patrimoine::where('domaine', 'ADS')->orderBy('numero_element')->get(),

            'Les Savoir-faire liés à l\'Artisanat Traditionnel (SFAT)' =>
                Patrimoine::where('domaine', 'SFAT')->orderBy('numero_element')->get(),

            'Les Traditions et expressions orales (TEO)' =>
                Patrimoine::where('domaine', 'TEO')->orderBy('numero_element')->get()
        ];

        return view('profil.edit', compact('demande', 'demandeur', 'domaines'));
    }

    /**
     * Mettre à jour une demande existante
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $demandeur = $user->demandeur;

        if (!$demandeur) {
            return redirect()->route('profil.index')->with('error', 'Profil demandeur introuvable.');
        }

        // Récupérer la demande
        $demande = Demande::with(['patrimoines'])->findOrFail($id);

        // Vérifier que la demande appartient au demandeur
        if ($demande->id_demandeur !== $demandeur->id_demandeur) {
            return redirect()->route('profil.index')->with('error', 'Vous n\'êtes pas autorisé à modifier cette demande.');
        }

        // Vérifier que la demande est en attente
        if ($demande->status !== 'en_attente') {
            return redirect()->route('profil.index')->with('error', 'Vous ne pouvez modifier que les demandes en attente.');
        }

        // Conversion des éléments en integers
        if ($request->has('elements_patrimoine')) {
            $request->merge([
                'elements_patrimoine' => array_map('intval', $request->elements_patrimoine)
            ]);
        }

        try {
            $validated = $request->validate([
                'type_detenteur' => 'required|in:individu,famille,communaute,autre',
                'nom' => 'required_if:type_detenteur,individu|nullable',
                'prenom' => 'required_if:type_detenteur,individu|nullable',
                'telephone' => 'required',
                'elements_patrimoine' => 'required|array|min:1',
                'elements_patrimoine.*' => 'integer|exists:patrimoines,id_element',
                'declaration' => 'required|accepted',
                'signature' => 'required',
                'autre_type_detenteur' => 'nullable',
                'photo' => 'nullable|image|max:2048',
                'date_naiss' => 'nullable|date',
                'lieu_naissance' => 'nullable',
                'sexe' => 'required_if:type_detenteur,individu|nullable|in:M,F,Autre',
                'groupe_etheroculturel' => 'nullable',
                'nom_structure' => 'required_if:type_detenteur,famille,communaute|nullable',
                'type_structure' => 'required_if:type_detenteur,famille,communaute|nullable',
                'province' => 'nullable',
                'commune' => 'nullable',
                'email' => 'nullable|email',
                'adresse' => 'nullable',
                'profession' => 'nullable',
            ]);

            DB::beginTransaction();

            try {
                // Mettre à jour le demandeur
                $this->mettreAJourDemandeur($demandeur, $validated);

                // Mettre à jour la demande
                $demande->update([
                    'signature' => $validated['signature'],
                ]);

                // Gérer la photo
                if ($request->hasFile('photo')) {
                    // Supprimer l'ancienne photo si elle existe
                    if ($demande->photo_path) {
                        \Storage::disk('public')->delete($demande->photo_path);
                    }
                    $this->stockerPhoto($request->file('photo'), $demande);
                }

                // Mettre à jour les éléments patrimoniaux
                $this->mettreAJourPatrimoines($validated['elements_patrimoine'], $demande);

                DB::commit();

                return redirect()->route('profil.index')
                    ->with('success', 'Votre demande a été modifiée avec succès.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Erreur lors de la modification: ' . $e->getMessage())->withInput();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    private function mettreAJourDemandeur($demandeur, $data)
    {
        $nom = $data['nom'] ?? null;
        $prenom = $data['prenom'] ?? null;

        if (in_array($data['type_detenteur'], ['famille', 'communaute']) && !empty($data['nom_structure'])) {
            $nom = $data['nom_structure'];
            $prenom = null;
        }

        $demandeur->update([
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naiss' => $data['date_naiss'] ?? null,
            'lieu_naissance' => $data['lieu_naissance'] ?? null,
            'telephone' => $data['telephone'],
            'sexe' => $data['sexe'] ?? 'Autre',
            'groupe_etheroculturel' => $data['groupe_etheroculturel'] ?? null,
            'email' => $data['email'] ?? null,
            'adresse' => $data['adresse'] ?? null,
            'profession' => $data['profession'] ?? null,
            'province' => $data['province'] ?? null,
            'commune' => $data['commune'] ?? null,
            'nom_structure' => $data['nom_structure'] ?? null,
            'type_structure' => $data['type_structure'] ?? null,
        ]);
    }

    private function mettreAJourPatrimoines($patrimoinesIds, $demande)
    {
        // Détacher tous les patrimoines actuels
        $demande->patrimoines()->detach();

        // Attacher les nouveaux patrimoines
        $demande->patrimoines()->attach($patrimoinesIds, [
            'relation_detenteur' => 'détenteur traditionnel',
            'anciennete' => null,
            'preuves_detention' => 'Déclaration sur l\'honneur'
        ]);
    }

    private function stockerPhoto($photo, $demande)
    {
        $path = $photo->store('photos/demandes', 'public');
        $demande->update(['photo_path' => $path]);
    }
}
