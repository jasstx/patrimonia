@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-2">Bienvenue</h4>
            <p>Vous êtes connecté. Utilisez la barre latérale si vous avez des accès spécifiques, sinon retour à l'accueil.</p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary">Aller à l'accueil</a>
        </div>
    </div>
    @auth
        @if(auth()->user()->isAdministrateur())
            <div class="mt-3">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Aller au Dashboard Admin</a>
            </div>
        @elseif(auth()->user()->isGestionnaire())
            <div class="mt-3">
                <a href="{{ route('gestionnaire.dashboard') }}" class="btn btn-primary">Aller au Dashboard Gestionnaire</a>
            </div>
        @endif
    @endauth
</div>
@endsection













