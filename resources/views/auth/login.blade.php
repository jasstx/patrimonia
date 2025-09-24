@extends('layouts.app')

@section('title', 'Connexion')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt"></i> Connexion</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Système d'authentification en cours de développement.
                    </div>

                    <p>Utilisez les comptes de test :</p>
                    <ul>
                        <li><strong>Admin:</strong> admin@patrimonia.bf / admin123</li>
                        <li><strong>Gestionnaire:</strong> gestionnaire@patrimonia.bf / gest123</li>
                    </ul>

                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
