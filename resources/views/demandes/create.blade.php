@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 rounded-3xl p-[1px] shadow-2xl">
        <div class="bg-white rounded-[calc(1.5rem-1px)] p-8">
            <div class="flex items-start gap-3 mb-8">
                <div class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-700 grid place-items-center">
                    📝
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Formulaire de demande</h2>
                    <p class="text-gray-500">Renseignez les informations ci‑dessous avec précision.</p>
                </div>
            </div>

    <form action="{{ route('demande.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10" x-data="{ domaine: '', typeDetenteur: '', sexe: '' }">
        @csrf

        {{-- IDENTIFICATION DU DETENTEUR --}}
        <div class="pb-8">
            <div class="flex items-center gap-3 mb-6">
                <span class="h-8 w-8 rounded-lg bg-indigo-600 text-white grid place-items-center text-sm font-semibold">1</span>
                <h3 class="text-xl font-semibold text-gray-900">Identification du détenteur</h3>
            </div>

            {{-- Type de détenteur --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4" x-data="{}">
                <label class="group relative cursor-pointer">
                    <input type="radio" name="type_detenteur" value="individu" required class="peer sr-only" x-model="typeDetenteur">
                    <div class="rounded-xl border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 group-hover:border-indigo-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                        Individu
                    </div>
                </label>
                <label class="group relative cursor-pointer">
                    <input type="radio" name="type_detenteur" value="famille" class="peer sr-only" x-model="typeDetenteur">
                    <div class="rounded-xl border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 group-hover:border-indigo-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                        Famille
                    </div>
                </label>
                <label class="group relative cursor-pointer">
                    <input type="radio" name="type_detenteur" value="communaute" class="peer sr-only" x-model="typeDetenteur">
                    <div class="rounded-xl border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 group-hover:border-indigo-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                        Communauté
                    </div>
                </label>
                <label class="group relative cursor-pointer">
                    <input type="radio" name="type_detenteur" value="autre" class="peer sr-only" x-model="typeDetenteur">
                    <div class="rounded-xl border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 group-hover:border-indigo-300 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 transition">
                        Autre
                    </div>
                </label>
            </div>

            {{-- Autre --}}
            <input type="text" name="autre_type_detenteur" placeholder="Préciser si autre"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 mb-4"
                   x-show="typeDetenteur === 'autre'" x-cloak
                   :required="typeDetenteur === 'autre'">

            {{-- Photo --}}
            <div>
                <label class="block font-medium text-gray-900 mb-2">Photo du détenteur</label>
                <input type="file" name="photo" class="block w-full border border-dashed border-gray-300 rounded-xl p-4 text-gray-600 hover:border-indigo-300 focus:border-indigo-400 focus:outline-none">
            </div>

            {{-- Champs individuels (individu) --}}
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4" x-show="typeDetenteur === 'individu'" x-cloak>
                <input type="text" name="nom" placeholder="Nom" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" :required="typeDetenteur === 'individu'">
                <input type="text" name="prenom" placeholder="Prénom" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" :required="typeDetenteur === 'individu'">
                <input type="date" name="date_naissance" placeholder="Date de naissance" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" :required="typeDetenteur === 'individu'">
                <input type="text" name="lieu_naissance" placeholder="Lieu de naissance" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" :required="typeDetenteur === 'individu'">
                <div class="md:col-span-2 grid grid-cols-2 gap-3">
                    <select name="sexe" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" :required="typeDetenteur === 'individu'" x-model="sexe">
                        <option value="">Sexe</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                    <input type="text" name="groupe_ethnique" placeholder="Groupe ethnique (optionnel)" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                </div>
            </div>

            {{-- Champs structure (famille/communauté) --}}
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4" x-show="typeDetenteur === 'famille' || typeDetenteur === 'communaute'" x-cloak>
                <input type="text" name="nom_structure" placeholder="Nom de la structure" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" :required="typeDetenteur === 'famille' || typeDetenteur === 'communaute'">
                <input type="text" name="type_structure" placeholder="Type de structure" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" :required="typeDetenteur === 'famille' || typeDetenteur === 'communaute'">
                <input type="text" name="siege_social" placeholder="Siège social" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" :required="typeDetenteur === 'famille' || typeDetenteur === 'communaute'">
                <input type="text" name="personne_contact" placeholder="Personne de contact" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" :required="typeDetenteur === 'famille' || typeDetenteur === 'communaute'">
            </div>

            {{-- Champs communs --}}
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="telephone" placeholder="Téléphone" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" required>
                <input type="text" name="localite_exercice" placeholder="Localité d'exercice" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400" required>
                <input type="email" name="email" placeholder="Email (optionnel)" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                <input type="text" name="adresse" placeholder="Adresse (optionnel)" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                <input type="text" name="profession" placeholder="Profession (optionnel)" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                <input type="text" name="coordonnees_gps" placeholder="Coordonnées GPS (optionnel)" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
            </div>
        </div>

        {{-- ELEMENTS DU PATRIMOINE --}}
        <div class="pb-8">
            <div class="flex items-center gap-3 mb-6">
                <span class="h-8 w-8 rounded-lg bg-indigo-600 text-white grid place-items-center text-sm font-semibold">2</span>
                <h3 class="text-xl font-semibold text-gray-900">Choix du domaine et des éléments</h3>
            </div>

            {{-- Sélecteur du domaine --}}
            <select x-model="domaine" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 mb-4">
                <option value="">-- Sélectionnez un domaine --</option>
                @foreach($domaines as $titre => $patrimoines)
                    <option value="{{ Str::slug($titre) }}">{{ $titre }}</option>
                @endforeach
            </select>

            {{-- Liste dynamique des éléments --}}
            @foreach($domaines as $titre => $patrimoines)
                <div x-show="domaine === '{{ Str::slug($titre) }}'" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($patrimoines as $p)
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl hover:border-indigo-300 hover:bg-indigo-50/40 transition">
                            <input type="checkbox" name="elements_patrimoine[]" value="{{ $p->id_element }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-gray-800 text-sm"><span class="font-semibold">{{ $p->initiale }}</span> — {{ $p->nom }}</span>
                        </label>
                    @endforeach
                </div>
            @endforeach
        </div>

        {{-- DECLARATION --}}
        <div class="pb-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="h-8 w-8 rounded-lg bg-indigo-600 text-white grid place-items-center text-sm font-semibold">3</span>
                <h3 class="text-xl font-semibold text-gray-900">Déclaration sur l’honneur</h3>
            </div>
            <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-xl">
                <input type="checkbox" name="declaration" required class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                <span class="text-gray-700">Je déclare sur l’honneur que toutes les informations fournies sont exactes.</span>
            </label>
            <input type="text" name="signature" placeholder="Signature (nom complet)" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 mt-3">
        </div>

        {{-- PIECES JOINTES --}}
        <div class="pb-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="h-8 w-8 rounded-lg bg-indigo-600 text-white grid place-items-center text-sm font-semibold">📎</span>
                <h3 class="text-xl font-semibold text-gray-900">Pièces jointes (optionnel)</h3>
            </div>
            <input type="file" name="pieces_jointes[]" multiple class="w-full border border-dashed border-gray-300 rounded-xl p-4 text-gray-600 hover:border-indigo-300 focus:border-indigo-400 focus:outline-none">
        </div>

        {{-- BOUTON --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ url()->previous() }}" class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">Annuler</a>
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-3 rounded-xl shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">
                <span>Envoyer la demande</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5"><path fill-rule="evenodd" d="M10.75 3.5a.75.75 0 0 0-1.5 0v6.69L6.03 6.97a.75.75 0 0 0-1.06 1.06l4.5 4.5c.3.3.77.3 1.06 0l4.5-4.5a.75.75 0 1 0-1.06-1.06l-3.22 3.22z" clip-rule="evenodd"/><path d="M4.25 12.5a.75.75 0 0 0-1.5 0V14A2.5 2.5 0 0 0 5.25 16.5h9.5A2.5 2.5 0 0 0 17.25 14v-1.5a.75.75 0 0 0-1.5 0V14c0 .55-.45 1-1 1h-9.5c-.55 0-1-.45-1-1z"/></svg>
            </button>
        </div>
    </form>
        </div>
    </div>
</div>

{{-- Alpine.js CDN (léger pour interactions) --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
