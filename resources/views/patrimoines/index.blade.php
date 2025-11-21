@extends('layouts.app')

@section('title', 'Éléments Patrimoniaux')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-landmark"></i> Éléments du Patrimoine National
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Cette fonctionnalité sera disponible prochainement.</p>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Catalogue complet des 191 éléments patrimoniaux en préparation.
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
