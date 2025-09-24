@extends('layouts.app')

@section('title', 'Accueil - Patrimonia')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <!-- En-tête -->
        <div class="text-center mb-5">
            <h1 class="display-4 text-primary">
                <i class="fas fa-landmark"></i> Patrimonia
            </h1>
            <p class="lead">Répertoire des Détenteurs d'Éléments du Patrimoine National du Burkina Faso</p>
        </div>

        <!-- Présentation -->
        <div class="row mb-5">
            <div class="col-md-8 mx-auto">
                <div class="card shadow">
                    <div class="card-body text-center">
                        <h3 class="card-title">Bienvenue sur Patrimonia</h3>
                        <p class="card-text">
                            Plateforme officielle de recensement et de gestion des détenteurs d'éléments
                            inscrits sur la liste du patrimoine national du Burkina Faso.
                        </p>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="text-primary">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <h5>Détenteurs</h5>
                                    <p>Recensement des gardiens du patrimoine</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-success">
                                    <i class="fas fa-list fa-3x mb-3"></i>
                                    <h5>Éléments</h5>
                                    <p>191 éléments patrimoniaux répertoriés</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-warning">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <h5>Demandes</h5>
                                    <p>Formulaire d'inscription en ligne</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-search fa-2x text-info mb-3"></i>
                        <h5>Consulter le Répertoire</h5>
                        <p>Accédez à la liste des détenteurs et éléments patrimoniaux</p>
                        <a href="{{ route('detenteurs.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Voir les détenteurs
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-edit fa-2x text-success mb-3"></i>
                        <h5>Soumettre une Demande</h5>
                        <p>Formulaire d'inscription comme détenteur de patrimoine</p>
                        <a href="{{ route('demande.create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Créer une demande
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Informations importantes</h6>
                    <p class="mb-0">
                        Cette plateforme est sous la supervision du
                        <strong>Ministère de la Communication, de la Culture, des Arts et du Tourisme</strong>
                        du Burkina Faso.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
