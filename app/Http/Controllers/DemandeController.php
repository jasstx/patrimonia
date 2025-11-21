<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\Demandeur;
use App\Models\Patrimoine;
use App\Models\PieceJointe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DemandeController extends Controller
{
    /**
     * Lister les demandes publiques avec filtres simples
     * Si l'utilisateur est connecté, afficher uniquement ses propres demandes
     */
    public function index(Request $request)
    {
        $query = Demande::with(['demandeur', 'patrimoines'])
            ->orderBy('created_at', 'desc');

        // Si l'utilisateur est connecté, afficher uniquement ses demandes
        if (Auth::check()) {
            $user = Auth::user();
            $demandeur = $user->demandeur;

            if ($demandeur) {
                $query->where('id_demandeur', $demandeur->id_demandeur);
            } else {
                // Pas de demandeur associé, retourner une collection vide
                $query->whereRaw('1 = 0'); // Force aucune résultat
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('q')) {
            $term = '%' . $request->string('q') . '%';
            $query->whereHas('demandeur', function ($q) use ($term) {
                $q->where('nom', 'like', $term)
                  ->orWhere('prenom', 'like', $term)
                  ->orWhere('telephone', 'like', $term)
                  ->orWhere('email', 'like', $term);
            });
        }

        $demandes = $query->paginate(15)->withQueryString();

        return view('demandes.index', compact('demandes'));
    }
    /**
     * Afficher le formulaire de demande
     */
    public function create()
    {
        try {
            // Test simple d'abord
            $totalPatrimoines = Patrimoine::count();

            if ($totalPatrimoines === 0) {
                return response('<h1>Aucun patrimoine en base de données</h1><p>Veuillez exécuter les seeders</p>');
            }

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

            // Debug info
            $debug = "Total patrimoines: $totalPatrimoines<br>";
            foreach ($domaines as $titre => $patrimoines) {
                $debug .= "$titre: " . $patrimoines->count() . " éléments<br>";
            }

            return view('demandes.create', compact('domaines'))->with('debug_info', $debug);
        } catch (\Exception $e) {
            return response('<h1>Erreur PHP</h1><pre>' . $e->getMessage() . "\n\n" . $e->getTraceAsString() . '</pre>');
        }
    }

    /**
     * Stocker une nouvelle demande
     */
    public function store(Request $request)
    {
        // DEBUG: Log des données reçues
        \Log::info('=== DÉBUT STORE ===');
        \Log::info('Données reçues:', $request->all());
        \Log::info('Fichiers reçus:', $request->allFiles());
        \Log::info('Utilisateur connecté:', Auth::user() ? Auth::user()->toArray() : ['status' => 'Non connecté']);

        // Conversion des éléments en integers
        if ($request->has('elements_patrimoine')) {
            $request->merge([
                'elements_patrimoine' => array_map('intval', $request->elements_patrimoine)
            ]);
        }

        // Normalisation de l'email pour éviter les doublons (espaces/casse)
        if ($request->filled('email')) {
            $email = trim(strtolower($request->input('email')));
            $request->merge(['email' => $email]);
        }

        try {
            // Validation complète et cohérente
            $validated = $request->validate([
                // Champs de base obligatoires
                'type_detenteur' => 'required|in:individu,famille,communaute,autre',
                'telephone' => 'required|string|max:20',
                'province' => 'required|string',
                'commune' => 'required|string',
                'elements_patrimoine' => 'required|array|min:1',
                'elements_patrimoine.*' => 'integer|exists:patrimoines,id_element',
                'declaration' => 'required|accepted',
                'signature' => 'required|string|min:3',

                // Champs conditionnels pour individu
                'nom' => 'required_if:type_detenteur,individu|nullable|string|max:100',
                'prenom' => 'required_if:type_detenteur,individu|nullable|string|max:100',
                'sexe' => 'required_if:type_detenteur,individu|nullable|in:M,F',
                'date_naiss' => 'nullable|date|before:today',
                'lieu_naissance' => 'nullable|string|max:100',
                'groupe_etheroculturel' => 'nullable|string|max:100',

                // Champs conditionnels pour famille/communauté
                'nom_structure' => 'required_if:type_detenteur,famille,communaute|nullable|string|max:200',
                'type_structure' => 'required_if:type_detenteur,famille,communaute|nullable|string|max:100',
                'siege_social' => 'nullable|string|max:200',
                'personne_contact' => 'nullable|string|max:100',

                // Champs conditionnels pour autre
                'autre_type_detenteur' => 'required_if:type_detenteur,autre|nullable|string|max:100',

                // Champs optionnels
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'email' => 'nullable|email|max:100|unique:demandeurs,email',
                'adresse' => 'nullable|string|max:500',
                'profession' => 'nullable|string|max:100',
                'coordonnees_geographiques' => 'nullable|string|max:100',
                'pieces_jointes' => 'nullable|array|max:5',
                'pieces_jointes.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',

                // Documents obligatoires selon le type de détenteur - INDIVIDU
                'cnib_individu' => 'required_if:type_detenteur,individu|nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'photo_identite_individu' => 'required_if:type_detenteur,individu|nullable|image|mimes:jpeg,png,jpg|max:5120',
                'description_element_individu' => 'required_if:type_detenteur,individu|nullable|string|max:2000',

                // Documents obligatoires selon le type de détenteur - FAMILLE
                'cnib_famille' => 'required_if:type_detenteur,famille|nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'attestation_famille' => 'required_if:type_detenteur,famille|nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'description_savoir_famille' => 'required_if:type_detenteur,famille|nullable|string|max:2000',
                'photo_groupe_famille' => 'required_if:type_detenteur,famille|nullable|image|mimes:jpeg,png,jpg|max:5120',

                // Documents obligatoires selon le type de détenteur - COMMUNAUTÉ
                'recepisse_communaute' => 'required_if:type_detenteur,communaute|nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'cnib_communaute' => 'required_if:type_detenteur,communaute|nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'description_element_communaute' => 'required_if:type_detenteur,communaute|nullable|string|max:2000',
            ], [
                'elements_patrimoine.required' => 'Veuillez sélectionner au moins un élément patrimonial.',
                'elements_patrimoine.*.exists' => 'Un élément patrimonial sélectionné est invalide.',
                'declaration.required' => 'Vous devez accepter la déclaration sur l\'honneur.',
                'telephone.required' => 'Le téléphone est obligatoire.',
                'province.required' => 'La province est obligatoire.',
                'commune.required' => 'La commune est obligatoire.',
                'nom_structure.required_if' => 'Le nom de la famille/communauté est obligatoire.',
                'type_structure.required_if' => 'Le type de famille/communauté est obligatoire.',
                'nom.required_if' => 'Le nom est obligatoire pour un individu.',
                'prenom.required_if' => 'Le prénom est obligatoire pour un individu.',
                'sexe.required_if' => 'Le sexe est obligatoire pour un individu.',
                'autre_type_detenteur.required_if' => 'Veuillez préciser le type de détenteur.',
                'signature.required' => 'La signature est obligatoire.',
                'signature.min' => 'La signature doit contenir au moins 3 caractères.',
                'email.unique' => 'Cet email existe déjà parmi les demandeurs. Veuillez utiliser un autre email.',
                'email.email' => 'Le format de l\'email est invalide.',
                'photo.image' => 'Le fichier photo doit être une image.',
                'photo.mimes' => 'La photo doit être au format JPEG, PNG ou JPG.',
                'photo.max' => 'La photo ne doit pas dépasser 2MB.',
                'pieces_jointes.max' => 'Vous ne pouvez joindre que 5 fichiers maximum.',
                'pieces_jointes.*.mimes' => 'Les pièces jointes doivent être au format PDF, JPG, PNG, DOC ou DOCX.',
                'pieces_jointes.*.max' => 'Chaque pièce jointe ne doit pas dépasser 5MB.',
                
                // Messages d'erreur pour les documents obligatoires - INDIVIDU
                'cnib_individu.required_if' => 'La copie CNIB/Passeport est obligatoire pour un individu.',
                'cnib_individu.mimes' => 'Le CNIB/Passeport doit être au format PDF, JPG ou PNG.',
                'cnib_individu.max' => 'Le CNIB/Passeport ne doit pas dépasser 5MB.',
                'photo_identite_individu.required_if' => 'La photo d\'identité est obligatoire pour un individu.',
                'photo_identite_individu.image' => 'La photo d\'identité doit être une image.',
                'photo_identite_individu.mimes' => 'La photo d\'identité doit être au format JPEG, PNG ou JPG.',
                'photo_identite_individu.max' => 'La photo d\'identité ne doit pas dépasser 5MB.',
                'description_element_individu.required_if' => 'La description de l\'élément culturel est obligatoire pour un individu.',
                'description_element_individu.max' => 'La description ne doit pas dépasser 2000 caractères.',

                // Messages d'erreur pour les documents obligatoires - FAMILLE
                'cnib_famille.required_if' => 'La CNIB du représentant de la famille est obligatoire.',
                'cnib_famille.mimes' => 'La CNIB doit être au format PDF, JPG ou PNG.',
                'cnib_famille.max' => 'La CNIB ne doit pas dépasser 5MB.',
                'attestation_famille.required_if' => 'L\'attestation coutumière/chefferie est obligatoire pour une famille.',
                'attestation_famille.mimes' => 'L\'attestation doit être au format PDF, JPG ou PNG.',
                'attestation_famille.max' => 'L\'attestation ne doit pas dépasser 5MB.',
                'description_savoir_famille.required_if' => 'La description du savoir détenu est obligatoire pour une famille.',
                'description_savoir_famille.max' => 'La description ne doit pas dépasser 2000 caractères.',
                'photo_groupe_famille.required_if' => 'La photo de groupe familial est obligatoire pour une famille.',
                'photo_groupe_famille.image' => 'La photo de groupe doit être une image.',
                'photo_groupe_famille.mimes' => 'La photo de groupe doit être au format JPEG, PNG ou JPG.',
                'photo_groupe_famille.max' => 'La photo de groupe ne doit pas dépasser 5MB.',

                // Messages d'erreur pour les documents obligatoires - COMMUNAUTÉ
                'recepisse_communaute.required_if' => 'Le récépissé ou les statuts de l\'association sont obligatoires pour une communauté.',
                'recepisse_communaute.mimes' => 'Le récépissé/statuts doit être au format PDF, JPG ou PNG.',
                'recepisse_communaute.max' => 'Le récépissé/statuts ne doit pas dépasser 5MB.',
                'cnib_communaute.required_if' => 'La CNIB du président ou représentant légal est obligatoire pour une communauté.',
                'cnib_communaute.mimes' => 'La CNIB doit être au format PDF, JPG ou PNG.',
                'cnib_communaute.max' => 'La CNIB ne doit pas dépasser 5MB.',
                'description_element_communaute.required_if' => 'La description de l\'élément culturel collectif est obligatoire pour une communauté.',
                'description_element_communaute.max' => 'La description ne doit pas dépasser 2000 caractères.',
            ]);

            // DEBUG: Vérifier la validation
            \Log::info('Validation réussie:', $validated);

            DB::beginTransaction();

            try {
                // 1. Récupérer ou créer le demandeur associé à l'utilisateur
                $user = Auth::user();
                $demandeur = null;

                // Pour chaque nouvelle demande, on crée toujours un nouveau demandeur
                // car un utilisateur peut avoir plusieurs demandes (individu, famille, communauté, etc.)
                \Log::info('=== CRÉATION DEMANDEUR ===');
                \Log::info('User actuel:', ['id' => $user ? $user->id : 'null', 'name' => $user ? $user->name : 'null', 'email' => $user ? $user->email : 'null']);
                \Log::info('Session ID:', ['session' => $request->session()->getId()]);
                \Log::info('Données de la demande:', [
                    'type_detenteur' => $validated['type_detenteur'],
                    'nom' => $validated['nom'] ?? null,
                    'telephone' => $validated['telephone']
                ]);

                $demandeur = $this->creerDemandeur($validated, $user);
                \Log::info('Nouveau demandeur créé:', $demandeur->toArray());
                \Log::info('User ID du demandeur:', ['user_id' => $demandeur->user_id]);

                // 2. Créer la demande
                $demande = $this->creerDemande($validated, $demandeur);
                \Log::info('Demande créée:', $demande->toArray());

                // 3. Gérer la photo
                if ($request->hasFile('photo')) {
                    $this->stockerPhoto($request->file('photo'), $demande);
                    \Log::info('Photo stockée');
                }

                // 4. Lier les éléments patrimoniaux
                $this->lierPatrimoines($validated['elements_patrimoine'], $demande);
                \Log::info('Patrimoines liés:', ['count' => $demande->patrimoines->count()]);

                // 5. Gérer les documents spécifiques selon le type de détenteur
                $this->stockerDocumentsSpecifiques($request, $demande, $validated['type_detenteur']);

                // 6. Gérer les pièces jointes optionnelles
                if ($request->hasFile('pieces_jointes')) {
                    $this->stockerPiecesJointes($request->file('pieces_jointes'), $demande);
                    \Log::info('Pièces jointes stockées');
                }

                DB::commit();
                \Log::info('Transaction commitée avec succès');

                return redirect()->route('demande.confirmation')
                               ->with('success', 'Votre demande a été soumise avec succès. Numéro de demande: ' . $demande->id_demande);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Erreur dans la transaction:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->with('error', 'Erreur lors de la création: ' . $e->getMessage())->withInput();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erreur de validation:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Erreur générale:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Erreur générale: ' . $e->getMessage())->withInput();
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
        //dd($demande);
        return view('demandes.show', compact('demande'));
    }

    private function creerDemandeur($data, $user = null)
    {
        // Pour les familles et communautés, utiliser nom_structure comme nom
        $nom = $data['nom'] ?? null;
        $prenom = $data['prenom'] ?? null;

        if (in_array($data['type_detenteur'], ['famille', 'communaute']) && !empty($data['nom_structure'])) {
            $nom = $data['nom_structure'];
            $prenom = null; // Null pour les familles/communautés
        }

        // Pour le type "autre", utiliser autre_type_detenteur comme nom
        if ($data['type_detenteur'] === 'autre' && !empty($data['autre_type_detenteur'])) {
            $nom = $data['autre_type_detenteur'];
            $prenom = null;
        }

        // Déterminer le sexe selon le type de détenteur
        $sexe = null;
        if ($data['type_detenteur'] === 'individu') {
            $sexe = $data['sexe'] ?? 'Autre';
        } else {
            // Pour familles/communautés, sexe = 'Autre' (pas de sexe spécifique)
            $sexe = 'Autre';
        }

        return Demandeur::create([
            'user_id' => $user ? $user->id : null,
            'nom' => $nom,
            'prenom' => $prenom,
            'date_naiss' => $data['date_naiss'] ?? null,
            'lieu_naissance' => $data['lieu_naissance'] ?? null,
            'telephone' => $data['telephone'],
            'sexe' => $sexe,
            'groupe_etheroculturel' => $data['groupe_etheroculturel'] ?? null,
            'email' => $data['email'] ?? null,
            'adresse' => $data['adresse'] ?? null,
            'profession' => $data['profession'] ?? null,
            'province' => $data['province'],
            'commune' => $data['commune'],
            'coordonnees_geographiques' => $data['coordonnees_geographiques'] ?? null,
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
            'anciennete' => null,
            'preuves_detention' => 'Déclaration sur l\'honneur'
        ]);
    }

    private function stockerDocumentsSpecifiques(Request $request, $demande, $typeDetenteur)
    {
        $documents = [];

        if ($typeDetenteur === 'individu') {
            if ($request->hasFile('cnib_individu')) {
                $documents[] = [
                    'file' => $request->file('cnib_individu'),
                    'description' => 'CNIB/Passeport - Individu'
                ];
            }
            if ($request->hasFile('photo_identite_individu')) {
                $documents[] = [
                    'file' => $request->file('photo_identite_individu'),
                    'description' => 'Photo d\'identité récente - Individu'
                ];
            }
            if ($request->filled('description_element_individu')) {
                // Créer un fichier texte pour stocker la description
                $description = $request->input('description_element_individu');
                $fileName = 'description_element_individu_' . $demande->id_demande . '_' . time() . '.txt';
                Storage::disk('public')->put('descriptions/demandes/' . $fileName, $description);
                
                PieceJointe::create([
                    'type_piece' => 'txt',
                    'nom_fichier' => 'description_element_individu.txt',
                    'chemin' => 'descriptions/demandes/' . $fileName,
                    'taille' => strlen($description),
                    'mime_type' => 'text/plain',
                    'date_ajout' => now(),
                    'id_demande' => $demande->id_demande,
                    'description' => $description,
                ]);
            }
        } elseif ($typeDetenteur === 'famille') {
            if ($request->hasFile('cnib_famille')) {
                $documents[] = [
                    'file' => $request->file('cnib_famille'),
                    'description' => 'CNIB du représentant de la famille'
                ];
            }
            if ($request->hasFile('attestation_famille')) {
                $documents[] = [
                    'file' => $request->file('attestation_famille'),
                    'description' => 'Attestation coutumière/Chefferie - Famille'
                ];
            }
            if ($request->hasFile('photo_groupe_famille')) {
                $documents[] = [
                    'file' => $request->file('photo_groupe_famille'),
                    'description' => 'Photo de groupe familial'
                ];
            }
            if ($request->filled('description_savoir_famille')) {
                $description = $request->input('description_savoir_famille');
                $fileName = 'description_savoir_famille_' . $demande->id_demande . '_' . time() . '.txt';
                Storage::disk('public')->put('descriptions/demandes/' . $fileName, $description);
                
                PieceJointe::create([
                    'type_piece' => 'txt',
                    'nom_fichier' => 'description_savoir_famille.txt',
                    'chemin' => 'descriptions/demandes/' . $fileName,
                    'taille' => strlen($description),
                    'mime_type' => 'text/plain',
                    'date_ajout' => now(),
                    'id_demande' => $demande->id_demande,
                    'description' => $description,
                ]);
            }
        } elseif ($typeDetenteur === 'communaute') {
            if ($request->hasFile('recepisse_communaute')) {
                $documents[] = [
                    'file' => $request->file('recepisse_communaute'),
                    'description' => 'Récépissé ou Statuts de l\'association - Communauté'
                ];
            }
            if ($request->hasFile('cnib_communaute')) {
                $documents[] = [
                    'file' => $request->file('cnib_communaute'),
                    'description' => 'CNIB du président ou représentant légal - Communauté'
                ];
            }
            if ($request->filled('description_element_communaute')) {
                $description = $request->input('description_element_communaute');
                $fileName = 'description_element_communaute_' . $demande->id_demande . '_' . time() . '.txt';
                Storage::disk('public')->put('descriptions/demandes/' . $fileName, $description);
                
                PieceJointe::create([
                    'type_piece' => 'txt',
                    'nom_fichier' => 'description_element_communaute.txt',
                    'chemin' => 'descriptions/demandes/' . $fileName,
                    'taille' => strlen($description),
                    'mime_type' => 'text/plain',
                    'date_ajout' => now(),
                    'id_demande' => $demande->id_demande,
                    'description' => $description,
                ]);
            }
        }

        // Stocker tous les documents
        foreach ($documents as $doc) {
            $path = $doc['file']->store('documents-specifiques/demandes', 'public');
            PieceJointe::create([
                'type_piece' => pathinfo($doc['file']->getClientOriginalName(), PATHINFO_EXTENSION),
                'nom_fichier' => $doc['file']->getClientOriginalName(),
                'chemin' => $path,
                'taille' => $doc['file']->getSize(),
                'mime_type' => $doc['file']->getMimeType(),
                'date_ajout' => now(),
                'id_demande' => $demande->id_demande,
                'description' => $doc['description'],
            ]);
        }
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
