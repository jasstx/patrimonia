@extends('layouts.app')

@section('title', 'Détails de la demande')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px 16px 0 0;
        margin-bottom: 2rem;
    }

    .detail-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #667eea;
    }

    .detail-card h4 {
        color: #667eea;
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .detail-row {
        display: flex;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #64748b;
        min-width: 150px;
    }

    .detail-value {
        color: #1e293b;
        flex: 1;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-en-attente {
        background: #fff3cd;
        color: #856404;
    }

    .status-validee {
        background: #d4edda;
        color: #155724;
    }

    .status-rejetee {
        background: #f8d7da;
        color: #721c24;
    }

    .status-en-cours {
        background: #d1ecf1;
        color: #0c5460;
    }

    .patrimoine-item {
        background: white;
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        border-left: 3px solid #667eea;
    }

    .photo-container {
        border-radius: 12px;
        overflow: hidden;
        width: 200px;
        height: 200px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pièce-jointe {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s;
    }

    .pièce-jointe:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }

    .pièce-jointe-icon {
        width: 40px;
        height: 40px;
        background: #667eea;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .document-preview {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        margin-top: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .description-text {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- En-tête avec bouton retour -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1"><i class="fas fa-file-alt"></i> Détails de la demande #{{ $demande->id_demande }}</h1>
            <p class="mb-0 opacity-75">Date de création : {{ $demande->created_at->format('d/m/Y à H:i') }}</p>
        </div>
        <div>
            <a href="{{ route('profil.index') }}" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-left"></i> Retour à mon profil
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Colonne gauche -->
        <div class="col-md-8">
            <!-- Informations sur le demandeur -->
            <div class="detail-card">
                <h4><i class="fas fa-user"></i> Informations du demandeur</h4>
                <div class="detail-row">
                    <span class="detail-label">Type de détenteur :</span>
                    <span class="detail-value">{{ ucfirst($demande->demandeur->type_detenteur ?? 'Non spécifié') }}</span>
                </div>
                @if($demande->demandeur)
                    <div class="detail-row">
                        <span class="detail-label">Nom :</span>
                        <span class="detail-value">{{ $demande->demandeur->nom }} {{ $demande->demandeur->prenom }}</span>
                    </div>
                    @if($demande->demandeur->date_naiss)
                    <div class="detail-row">
                        <span class="detail-label">Date de naissance :</span>
                        <span class="detail-value">{{ $demande->demandeur->date_naiss->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    @if($demande->demandeur->lieu_naissance)
                    <div class="detail-row">
                        <span class="detail-label">Lieu de naissance :</span>
                        <span class="detail-value">{{ $demande->demandeur->lieu_naissance }}</span>
                    </div>
                    @endif
                    @if($demande->demandeur->sexe)
                    <div class="detail-row">
                        <span class="detail-label">Sexe :</span>
                        <span class="detail-value">{{ $demande->demandeur->sexe == 'M' ? 'Masculin' : ($demande->demandeur->sexe == 'F' ? 'Féminin' : 'Autre') }}</span>
                    </div>
                    @endif
                    @if($demande->demandeur->groupe_etheroculturel)
                    <div class="detail-row">
                        <span class="detail-label">Groupe ethnoculturel :</span>
                        <span class="detail-value">{{ $demande->demandeur->groupe_etheroculturel }}</span>
                    </div>
                    @endif
                @endif
            </div>

            <!-- Contact -->
            <div class="detail-card">
                <h4><i class="fas fa-address-card"></i> Informations de contact</h4>
                @if($demande->demandeur)
                    <div class="detail-row">
                        <span class="detail-label">Téléphone :</span>
                        <span class="detail-value">{{ $demande->demandeur->telephone }}</span>
                    </div>
                    @if($demande->demandeur->email)
                    <div class="detail-row">
                        <span class="detail-label">Email :</span>
                        <span class="detail-value">{{ $demande->demandeur->email }}</span>
                    </div>
                    @endif
                    @if($demande->demandeur->profession)
                    <div class="detail-row">
                        <span class="detail-label">Profession :</span>
                        <span class="detail-value">{{ $demande->demandeur->profession }}</span>
                    </div>
                    @endif
                    @if($demande->demandeur->adresse)
                    <div class="detail-row">
                        <span class="detail-label">Adresse :</span>
                        <span class="detail-value">{{ $demande->demandeur->adresse }}</span>
                    </div>
                    @endif
                    @if($demande->demandeur->province)
                    <div class="detail-row">
                        <span class="detail-label">Province :</span>
                        <span class="detail-value">{{ $demande->demandeur->province }}</span>
                    </div>
                    @endif
                    @if($demande->demandeur->commune)
                    <div class="detail-row">
                        <span class="detail-label">Commune :</span>
                        <span class="detail-value">{{ $demande->demandeur->commune }}</span>
                    </div>
                    @endif
                    @if($demande->demandeur->nom_structure && in_array($demande->demandeur->type_detenteur, ['famille', 'communaute']))
                    <div class="detail-row">
                        <span class="detail-label">Nom de la structure :</span>
                        <span class="detail-value">{{ $demande->demandeur->nom_structure }}</span>
                    </div>
                    @endif
                    @if($demande->demandeur->type_structure && in_array($demande->demandeur->type_detenteur, ['famille', 'communaute']))
                    <div class="detail-row">
                        <span class="detail-label">Type de structure :</span>
                        <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $demande->demandeur->type_structure)) }}</span>
                    </div>
                    @endif
                @endif
            </div>

            <!-- Éléments patrimoniaux -->
            <div class="detail-card">
                <h4><i class="fas fa-landmark"></i> Éléments patrimoniaux</h4>
                @if($demande->patrimoines->count() > 0)
                    @foreach($demande->patrimoines as $patrimoine)
                    <div class="patrimoine-item">
                        <strong>{{ $patrimoine->domaine }}-{{ $patrimoine->numero_element }}</strong>
                        <div class="text-muted">{{ $patrimoine->nom }}</div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">Aucun élément patrimonial</p>
                @endif
            </div>

            <!-- Documents spécifiques selon le type de détenteur -->
            @php
                $documentsSpecifiques = $demande->piecesJointes->filter(function($piece) {
                    return strpos($piece->description, 'CNIB') !== false 
                        || strpos($piece->description, 'Passeport') !== false
                        || strpos($piece->description, 'Photo') !== false
                        || strpos($piece->description, 'Attestation') !== false
                        || strpos($piece->description, 'Récépissé') !== false
                        || strpos($piece->description, 'Statuts') !== false
                        || strpos($piece->description, 'description_') !== false;
                });
                
                $piecesJointesOptionnelles = $demande->piecesJointes->filter(function($piece) {
                    return strpos($piece->description, 'Pièce jointe:') !== false;
                });
                
                $descriptions = $demande->piecesJointes->filter(function($piece) {
                    return strpos($piece->nom_fichier, 'description_') !== false && $piece->type_piece === 'txt';
                });
            @endphp

            @if($documentsSpecifiques->count() > 0 || $descriptions->count() > 0)
            <div class="detail-card">
                <h4><i class="fas fa-file-alt"></i> Documents obligatoires</h4>
                <p class="text-muted mb-3" style="font-size: 0.9rem;">
                    Documents fournis selon le type de détenteur ({{ ucfirst($demande->demandeur->type_detenteur ?? 'individu') }})
                </p>
                
                @foreach($documentsSpecifiques as $piece)
                    @if($piece->type_piece !== 'txt')
                    <div class="pièce-jointe mb-3" style="border: 1px solid #e2e8f0;">
                        <div class="pièce-jointe-icon" style="background: {{ $piece->mime_type == 'application/pdf' ? '#dc2626' : ($piece->mime_type == 'image/jpeg' || $piece->mime_type == 'image/png' ? '#10b981' : '#667eea') }};">
                            @if($piece->mime_type == 'application/pdf')
                                <i class="fas fa-file-pdf"></i>
                            @elseif(strpos($piece->mime_type, 'image/') === 0)
                                <i class="fas fa-image"></i>
                            @else
                                <i class="fas fa-file"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $piece->description }}</div>
                            <div class="text-muted" style="font-size: 0.85rem;">
                                <i class="fas fa-file"></i> {{ $piece->nom_fichier }}
                            </div>
                            <small class="text-muted">{{ number_format($piece->taille/1024, 1) }} Ko · {{ $piece->mime_type }}</small>
                            
                            @if(strpos($piece->mime_type, 'image/') === 0)
                            <div class="mt-2">
                                <a href="{{ asset('storage/'.$piece->chemin) }}" target="_blank" class="d-inline-block">
                                    <img src="{{ asset('storage/'.$piece->chemin) }}" alt="{{ $piece->description }}" class="document-preview">
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ asset('storage/'.$piece->chemin) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-{{ strpos($piece->mime_type, 'image/') === 0 ? 'eye' : 'download' }}"></i> {{ strpos($piece->mime_type, 'image/') === 0 ? 'Voir' : 'Télécharger' }}
                            </a>
                        </div>
                    </div>
                    @endif
                @endforeach

                @foreach($descriptions as $piece)
                <div class="pièce-jointe mb-3" style="border: 1px solid #e2e8f0; background: #f8fafc;">
                    <div class="pièce-jointe-icon" style="background: #f59e0b;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">
                            @if(strpos($piece->nom_fichier, 'individu') !== false)
                                Description de l'élément culturel
                            @elseif(strpos($piece->nom_fichier, 'famille') !== false)
                                Description du savoir détenu
                            @elseif(strpos($piece->nom_fichier, 'communaute') !== false)
                                Description de l'élément culturel collectif
                            @else
                                Description
                            @endif
                        </div>
                        <div class="mt-2 p-2 bg-white rounded description-text" style="border-left: 3px solid #f59e0b; font-size: 0.9rem; line-height: 1.6;">
                            {{ $piece->description }}
                        </div>
                        <small class="text-muted mt-1 d-block">{{ number_format($piece->taille) }} caractères</small>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Pièces jointes optionnelles -->
            @if($piecesJointesOptionnelles->count() > 0)
            <div class="detail-card">
                <h4><i class="fas fa-paperclip"></i> Pièces jointes supplémentaires</h4>
                <p class="text-muted mb-3" style="font-size: 0.9rem;">
                    Documents additionnels fournis par le demandeur
                </p>
                @foreach($piecesJointesOptionnelles as $piece)
                <a href="{{ asset('storage/'.$piece->chemin) }}" target="_blank" class="pièce-jointe text-decoration-none text-reset mb-2">
                    <div class="pièce-jointe-icon" style="background: #64748b;">
                        @if($piece->mime_type == 'application/pdf')
                            <i class="fas fa-file-pdf"></i>
                        @elseif(strpos($piece->mime_type, 'image/') === 0)
                            <i class="fas fa-image"></i>
                        @elseif(strpos($piece->mime_type, 'application/msword') !== false || strpos($piece->mime_type, 'wordprocessingml') !== false)
                            <i class="fas fa-file-word"></i>
                        @else
                            <i class="fas fa-file"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ $piece->nom_fichier }}</div>
                        <small class="text-muted">{{ number_format($piece->taille/1024, 1) }} Ko · {{ $piece->mime_type }}</small>
                    </div>
                    <i class="fas fa-download text-muted"></i>
                </a>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Colonne droite -->
        <div class="col-md-4">
            <!-- Statut -->
            <div class="card mb-4" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3">Statut de la demande</h5>
                    <span class="status-badge status-{{ str_replace('_', '-', $demande->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $demande->status)) }}
                    </span>
                </div>
            </div>

            <!-- Photo -->
            @if($demande->photo_path)
            <div class="card mb-4" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3">Photo</h5>
                    <div class="photo-container mx-auto">
                       <img src="{{ asset('storage/' . $demande->photo_path) }}" alt="Photo du détenteur">
                    </div>
                </div>
            </div>
            @endif

            <!-- Informations supplémentaires -->
            <div class="card" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informations générales</h5>
                    <div class="detail-row">
                        <span class="detail-label">Signature :</span>
                        <span class="detail-value">{{ $demande->signature }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date de création :</span>
                        <span class="detail-value">{{ $demande->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    @if($demande->patrimoines->count() > 0)
                    <div class="detail-row">
                        <span class="detail-label">Nombre d'éléments :</span>
                        <span class="detail-value">{{ $demande->patrimoines->count() }}</span>
                    </div>
                    @endif
                    @if($demande->piecesJointes->count() > 0)
                    <div class="detail-row">
                        <span class="detail-label">Pièces jointes :</span>
                        <span class="detail-value">{{ $demande->piecesJointes->count() }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection









