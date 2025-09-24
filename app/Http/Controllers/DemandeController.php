<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Demandeur;
use App\Models\Patrimoine;
use App\Models\PieceJointe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DemandeController extends Controller
{
    /**
     * Afficher le formulaire de demande
     */
    public function create()
{
    // Version simple et directe
    $domaines = [
        'Les Connaissances et Pratiques liées à la nature et à l\'Univers (CPNU)' =>
            \App\Models\Patrimoine::where('domaine', 'CPNU')->orderBy('numero_element')->get(),

        'Les Pratiques Sociales, les Rites et les Événements Festifs (PSREF)' =>
            \App\Models\Patrimoine::where('domaine', 'PSREF')->orderBy('numero_element')->get(),

        'Les Arts du Spectacle (ADS)' =>
            \App\Models\Patrimoine::where('domaine', 'ADS')->orderBy('numero_element')->get(),

        'Les Savoir-faire liés à l\'Artisanat Traditionnel (SFAT)' =>
            \App\Models\Patrimoine::where('domaine', 'SFAT')->orderBy('numero_element')->get(),

        'Les Traditions et expressions orales (TEO)' =>
            \App\Models\Patrimoine::where('domaine', 'TEO')->orderBy('numero_element')->get()
    ];

    return view('demandes.create', compact('domaines'));
}
    /**
     * Stocker une nouvelle demande
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            // Informations détenteur
            'type_detenteur' => 'required|in:individu,famille,communaute,autre',
            'autre_type_detenteur' => 'required_if:type_detenteur,autre',
            'photo' => 'nullable|image|max:2048',

            // Informations individu
            'nom' => 'required_if:type_detenteur,individu',
            'prenom' => 'required_if:type_detenteur,individu',
            'date_naissance' => 'required_if:type_detenteur,individu|date',
            'lieu_naissance' => 'required_if:type_detenteur,individu',
            'sexe' => 'required_if:type_detenteur,individu|in:M,F',
            'groupe_ethnique' => 'nullable',

            // Informations famille/communauté
            'nom_structure' => 'required_if:type_detenteur,famille,communaute',
            'type_structure' => 'required_if:type_detenteur,famille,communaute',
            'siege_social' => 'required_if:type_detenteur,famille,communaute',
            'personne_contact' => 'required_if:type_detenteur,famille,communaute',

            // Informations communes
            'localite_exercice' => 'required',
            'telephone' => 'required',
            'coordonnees_gps' => 'nullable',
            'email' => 'nullable|email',
            'adresse' => 'nullable',
            'profession' => 'nullable',

            // Éléments patrimoniaux
            'elements_patrimoine' => 'required|array|min:1',
            'elements_patrimoine.*' => 'exists:patrimoines,id_element',

            // Déclaration
            'declaration' => 'required|accepted',
            'signature' => 'required',

            // Pièces jointes
            'pieces_jointes' => 'nullable|array',
            'pieces_jointes.*' => 'file|max:5120', // 5MB max
        ]);

        DB::beginTransaction();

        try {
            // 1. Créer le demandeur
            $demandeur = $this->creerDemandeur($validated);

            // 2. Créer la demande
            $demande = $this->creerDemande($validated, $demandeur);

            // 3. Gérer la photo
            if ($request->hasFile('photo')) {
                $this->stockerPhoto($request->file('photo'), $demande);
            }

            // 4. Lier les éléments patrimoniaux
            $this->lierPatrimoines($validated['elements_patrimoine'], $demande);

            // 5. Gérer les pièces jointes
            if ($request->hasFile('pieces_jointes')) {
                $this->stockerPiecesJointes($request->file('pieces_jointes'), $demande);
            }

            DB::commit();

            return redirect()->route('demande.confirmation')
                           ->with('success', 'Votre demande a été soumise avec succès. Numéro de demande: ' . $demande->id_demande);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Page de confirmation
     */
    public function confirmation()
    {
        return view('demandes.confirmation');
    }

    /**
     * Afficher une demande
     */
    public function show($id)
    {
        $demande = Demande::with(['demandeur', 'patrimoines', 'piecesJointes'])->findOrFail($id);
        return view('demandes.show', compact('demande'));
    }

    private function creerDemandeur($data)
    {
        return Demandeur::create([
            'nom' => $data['nom'] ?? '',
            'prenom' => $data['prenom'] ?? '',
            'date_naiss' => $data['date_naissance'] ?? null,
            'lieu_naissance' => $data['lieu_naissance'] ?? null,
            'telephone' => $data['telephone'],
            'sexe' => $data['sexe'] ?? null,
            'groupe_etheroculturel' => $data['groupe_ethnique'] ?? null,
            'email' => $data['email'] ?? null,
            'adresse' => $data['adresse'] ?? null,
            'profession' => $data['profession'] ?? null,
            'localite_exercice' => $data['localite_exercice'],
            'coordonnees_geographiques' => $data['coordonnees_gps'] ?? null,
            'type_detenteur' => $data['type_detenteur'],
            'autre_type_detenteur' => $data['autre_type_detenteur'] ?? null,
            'nom_structure' => $data['nom_structure'] ?? null,
            'type_structure' => $data['type_structure'] ?? null,
            'siege_social' => $data['siege_social'] ?? null,
            'personne_contact' => $data['personne_contact'] ?? null,
        ]);
    }

    private function creerDemande($data, $demandeur)
    {
        return Demande::create([
            'type_demande' => 'inscription_detenteur',
            'date_creation' => now(),
            'status' => 'en_attente',
            'id_demandeur' => $demandeur->id_demandeur,
            'declaration_honneur' => true,
            'date_declaration' => now(),
            'signature' => $data['signature'],
        ]);
    }

    private function stockerPhoto($photo, $demande)
    {
        $path = $photo->store('photos/demandes', 'public');
        $demande->update(['photo_path' => $path]);
    }

    private function lierPatrimoines($patrimoinesIds, $demande)
    {
        $demande->patrimoines()->attach($patrimoinesIds, [
            'relation_detenteur' => 'détenteur traditionnel',
            'anciennete' => null, // À compléter selon le formulaire
            'preuves_detention' => 'Déclaration sur l\'honneur'
        ]);
    }

    private function stockerPiecesJointes($pieces, $demande)
    {
        foreach ($pieces as $piece) {
            $path = $piece->store('pieces-jointes/demandes', 'public');

            PieceJointe::create([
                'type_piece' => pathinfo($piece->getClientOriginalName(), PATHINFO_EXTENSION),
                'nom_fichier' => $piece->getClientOriginalName(),
                'chemin' => $path,
                'taille' => $piece->getSize(),
                'mime_type' => $piece->getMimeType(),
                'date_ajout' => now(),
                'id_demande' => $demande->id_demande,
                'description' => 'Pièce jointe: ' . $piece->getClientOriginalName(),
            ]);
        }
    }
}
