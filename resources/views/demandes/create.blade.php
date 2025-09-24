@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8 mt-10">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">📝 Formulaire de demande</h2>

    <form action="{{ route('demande.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ domaine: '' }">
        @csrf

        {{-- IDENTIFICATION DU DETENTEUR --}}
        <div class="border-b pb-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">1️⃣ Identification du détenteur</h3>

            {{-- Type de détenteur --}}
            <div class="flex gap-4 mb-4">
                <label><input type="radio" name="type_detenteur" value="individu" required> Individu</label>
                <label><input type="radio" name="type_detenteur" value="famille"> Famille</label>
                <label><input type="radio" name="type_detenteur" value="communaute"> Communauté</label>
                <label><input type="radio" name="type_detenteur" value="autre"> Autre</label>
            </div>

            {{-- Autre --}}
            <input type="text" name="autre_type_detenteur" placeholder="Préciser si autre"
                   class="w-full p-3 border rounded-lg mb-4 hidden" id="autre-field">

            {{-- Photo --}}
            <div>
                <label class="block font-medium text-gray-600 mb-2">Photo du détenteur</label>
                <input type="file" name="photo" class="block w-full border p-2 rounded-lg">
            </div>

            {{-- Exemple minimal : Nom, Prénom, Téléphone --}}
            <div class="mt-4 space-y-3">
                <input type="text" name="nom" placeholder="Nom" class="w-full p-3 border rounded-lg">
                <input type="text" name="prenom" placeholder="Prénom" class="w-full p-3 border rounded-lg">
                <input type="text" name="telephone" placeholder="Téléphone" class="w-full p-3 border rounded-lg">
                <input type="text" name="localite_exercice" placeholder="Localité d'exercice" class="w-full p-3 border rounded-lg">
            </div>
        </div>

        {{-- ELEMENTS DU PATRIMOINE --}}
        <div class="border-b pb-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">2️⃣ Choix du domaine et des éléments</h3>

            {{-- Sélecteur du domaine --}}
            <select x-model="domaine" class="w-full p-3 border rounded-lg mb-4">
                <option value="">-- Sélectionnez un domaine --</option>
                @foreach($domaines as $titre => $patrimoines)
                    <option value="{{ Str::slug($titre) }}">{{ $titre }}</option>
                @endforeach
            </select>

            {{-- Liste dynamique des éléments --}}
            @foreach($domaines as $titre => $patrimoines)
                <div x-show="domaine === '{{ Str::slug($titre) }}'" class="mt-4 space-y-2">
                    @foreach($patrimoines as $p)
                        <label class="flex items-center gap-2 p-2 border rounded hover:bg-gray-50">
                            <input type="checkbox" name="elements_patrimoine[]" value="{{ $p->id_element }}">
                            {{ $p->initiale }} - {{ $p->nom }}
                        </label>
                    @endforeach
                </div>
            @endforeach
        </div>

        {{-- DECLARATION --}}
        <div>
            <h3 class="text-xl font-semibold text-gray-700 mb-4">3️⃣ Déclaration sur l’honneur</h3>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="declaration" required>
                Je déclare sur l’honneur que toutes les informations fournies sont exactes.
            </label>
            <input type="text" name="signature" placeholder="Signature (nom complet)" class="w-full p-3 border rounded-lg mt-2">
        </div>

        {{-- PIECES JOINTES --}}
        <div>
            <h3 class="text-xl font-semibold text-gray-700 mb-4">📎 Pièces jointes (optionnel)</h3>
            <input type="file" name="pieces_jointes[]" multiple class="w-full border p-2 rounded-lg">
        </div>

        {{-- BOUTON --}}
        <div class="text-right">
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow hover:bg-indigo-700">
                ✅ Envoyer la demande
            </button>
        </div>
    </form>
</div>

{{-- Alpine.js CDN --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

{{-- Script pour afficher champ "autre" --}}
<script>
    const radios = document.querySelectorAll('input[name="type_detenteur"]');
    radios.forEach(r => r.addEventListener('change', function() {
        document.getElementById('autre-field').classList.add('hidden');
        if (this.value === 'autre') {
            document.getElementById('autre-field').classList.remove('hidden');
        }
    }));
</script>
@endsection
