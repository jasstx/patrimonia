@extends('layouts.app')

@section('title', 'Liste des Détenteurs')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-users"></i> Liste des Détenteurs de Patrimoine
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Cette fonctionnalité sera disponible prochainement.</p>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Le répertoire complet des détenteurs est en cours de constitution.
                    </div>

                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
