@extends('layouts.app')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = { corePlugins: { preflight: false } };
</script>
@endpush

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-xl rounded-2xl p-8 mt-10">
    <h2 class="text-2xl font-bold mb-4 text-green-700">🎉 Demande soumise avec succès</h2>
    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('demande_id'))
        <p class="text-gray-700 mb-2">Identifiant de votre demande: <strong>#{{ session('demande_id') }}</strong></p>
    @endif
    <p class="text-gray-700">Nous vous contacterons après traitement. Vous pouvez revenir à l'accueil.</p>

    <div class="mt-6">
        <a href="{{ route('home') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">🏠 Retour à l'accueil</a>
    </div>
</div>
@endsection



