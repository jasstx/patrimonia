@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#FF0000; --secondary:#00AA00; --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    
    body { font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; background:linear-gradient(135deg,#f8fafc 0%,#e2e8f0 100%); min-height:100vh; padding:2rem 1rem; }
    .container { max-width:1200px; margin:0 auto; }

    /* Alert */
    .alert { padding:1rem 1.5rem; border-radius:12px; margin-bottom:1.5rem; display:flex; align-items:center; gap:1rem; animation:slideDown .3s ease; }
    @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
    .alert-success { background:rgba(16,185,129,.1); border:2px solid var(--success); color:var(--success); }

    /* Page Header */
    .page-header { background:#fff; border-radius:20px; padding:2rem; margin-bottom:2rem; box-shadow:0 4px 20px rgba(0,0,0,.05); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .header-left { display:flex; align-items:center; gap:1rem; }
    .header-icon { width:60px; height:60px; background:linear-gradient(135deg,var(--primary),var(--secondary)); border-radius:15px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; color:#fff; }
    .page-header h1 { font-size:2rem; font-weight:800; color:var(--dark); margin-bottom:.25rem; }
    .page-header p { color:var(--gray); font-size:.95rem; }
    .btn { padding:.75rem 1.5rem; border-radius:10px; font-weight:600; cursor:pointer; transition:all .3s ease; border:none; display:inline-flex; align-items:center; gap:.5rem; text-decoration:none; font-size:.95rem; }
    .btn-secondary { background:#fff; color:var(--gray); border:2px solid #e2e8f0; }
    .btn-secondary:hover { background:var(--light); border-color:var(--gray); }

    /* Status Badge */
    .status-badge { display:inline-flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border-radius:20px; font-size:.9rem; font-weight:600; }
    .status-pending { background:rgba(245,158,11,.1); color:var(--warning); }
    .status-progress { background:rgba(59,130,246,.1); color:var(--primary); }
    .status-validated { background:rgba(16,185,129,.1); color:var(--success); }
    .status-rejected { background:rgba(239,68,68,.1); color:var(--danger); }

    /* Content Grid */
    .content-grid { display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin-bottom:2rem; }
    .info-card { background:#fff; border-radius:16px; padding:2rem; box-shadow:0 4px 15px rgba(0,0,0,.05); }
    .card-title { font-size:1.2rem; font-weight:700; color:var(--dark); margin-bottom:1.5rem; display:flex; align-items:center; gap:.75rem; }
    .card-title i { width:40px; height:40px; background:linear-gradient(135deg,var(--primary),var(--secondary)); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.2rem; }
    .info-item { display:flex; justify-content:space-between; align-items:center; padding:.75rem 0; border-bottom:1px solid #f1f5f9; }
    .info-item:last-child { border-bottom:none; }
    .info-label { font-weight:600; color:var(--gray); font-size:.9rem; }
    .info-value { color:var(--dark); font-weight:500; }

    /* Patrimoines Section */
    .patrimoines-section { background:#fff; border-radius:16px; padding:2rem; box-shadow:0 4px 15px rgba(0,0,0,.05); margin-bottom:2rem; }
    .patrimoine-item { background:#f8fafc; border-radius:12px; padding:1rem; margin-bottom:.75rem; display:flex; align-items:center; gap:1rem; }
    .patrimoine-item:last-child { margin-bottom:0; }
    .patrimoine-code { background:var(--primary); color:#fff; padding:.5rem .75rem; border-radius:8px; font-family:monospace; font-weight:600; font-size:.85rem; }
    .patrimoine-name { color:var(--dark); font-weight:600; }
    .patrimoine-domain { color:var(--gray); font-size:.9rem; }

    /* Actions Section */
    .actions-section { background:#fff; border-radius:16px; padding:2rem; box-shadow:0 4px 15px rgba(0,0,0,.05); }
    .actions-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
    .action-form { display:flex; flex-direction:column; gap:1rem; }
    .form-group { display:flex; flex-direction:column; gap:.5rem; }
    .form-group label { font-weight:600; color:var(--dark); font-size:.9rem; }
    .form-control { padding:.75rem 1rem; border:2px solid #e2e8f0; border-radius:10px; font-size:.95rem; transition:all .3s ease; }
    .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    .btn-success { background:linear-gradient(135deg,var(--success),#059669); color:#fff; box-shadow:0 4px 15px rgba(16,185,129,.3); }
    .btn-success:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(16,185,129,.4); }
    .btn-danger { background:linear-gradient(135deg,var(--danger),#dc2626); color:#fff; box-shadow:0 4px 15px rgba(239,68,68,.3); }
    .btn-danger:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(239,68,68,.4); }

    /* Empty State */
    .empty-state { text-align:center; padding:3rem 2rem; color:var(--gray); }
    .empty-state i { font-size:3rem; margin-bottom:1rem; opacity:.3; }
    .empty-state h3 { font-size:1.3rem; color:var(--dark); margin-bottom:.5rem; }

    /* Documents Section */
    .document-item { background:#f8fafc; border-radius:12px; padding:1rem; margin-bottom:.75rem; display:flex; align-items:center; gap:1rem; border:1px solid #e2e8f0; transition:all .2s ease; }
    .document-item:hover { background:#fff; box-shadow:0 2px 8px rgba(0,0,0,.1); transform:translateX(4px); }
    .document-icon { width:50px; height:50px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.3rem; flex-shrink:0; }
    .document-info { flex:1; }
    .document-name { font-weight:700; color:var(--dark); font-size:.95rem; margin-bottom:.25rem; }
    .document-meta { font-size:.85rem; color:var(--gray); }
    .document-preview-img { max-width:100%; max-height:200px; border-radius:8px; margin-top:.5rem; box-shadow:0 2px 8px rgba(0,0,0,.1); }
    .description-box { background:#fff; border-left:3px solid #f59e0b; padding:1rem; border-radius:8px; margin-top:.5rem; font-size:.9rem; line-height:1.6; white-space:pre-wrap; word-wrap:break-word; }

    /* Responsive */
    @media (max-width:768px) {
        body { padding:1rem .5rem; }
        .page-header { flex-direction:column; align-items:flex-start; }
        .content-grid { grid-template-columns:1fr; }
        .actions-grid { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')
<div class="container">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle" style="font-size:1.5rem;"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <div class="header-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <h1>Demande #{{ $demande->id_demande }}</h1>
                <p>Détails et validation de la demande d'inscription</p>
            </div>
        </div>
        <a href="{{ route('gestionnaire.demandes') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Status Badge -->
    <div style="margin-bottom:2rem; text-align:center;">
        @php
            $statusClass = match($demande->status) {
                'en_attente' => 'status-pending',
                'en_cours' => 'status-progress',
                'valide' => 'status-validated',
                'rejete' => 'status-rejected',
                default => 'status-pending'
            };
            $statusIcon = match($demande->status) {
                'en_attente' => 'fas fa-clock',
                'en_cours' => 'fas fa-spinner',
                'valide' => 'fas fa-check',
                'rejete' => 'fas fa-times',
                default => 'fas fa-clock'
            };
            $statusText = match($demande->status) {
                'en_attente' => 'En attente',
                'en_cours' => 'En cours',
                'valide' => 'Validé',
                'rejete' => 'Rejeté',
                default => ucfirst($demande->status)
            };
        @endphp
        <span class="status-badge {{ $statusClass }}">
            <i class="{{ $statusIcon }}"></i> {{ $statusText }}
        </span>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Demandeur Info -->
        <div class="info-card">
            <h3 class="card-title">
                <i class="fas fa-user"></i>
                Informations du Demandeur
            </h3>
            <div class="info-item">
                <span class="info-label">Nom complet</span>
                <span class="info-value">{{ optional($demande->demandeur)->nom }} {{ optional($demande->demandeur)->prenom }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Type de détenteur</span>
                <span class="info-value">{{ optional($demande->demandeur)->type_detenteur_formate ?? 'Individu' }}</span>
            </div>
            @if(optional($demande->demandeur)->type_detenteur === 'autre' && optional($demande->demandeur)->autre_type_detenteur)
            <div class="info-item">
                <span class="info-label">Autre type</span>
                <span class="info-value">{{ optional($demande->demandeur)->autre_type_detenteur }}</span>
            </div>
            @endif
            @if(in_array(optional($demande->demandeur)->type_detenteur, ['famille', 'communaute']))
            <div class="info-item">
                <span class="info-label">Nom de la {{ optional($demande->demandeur)->type_detenteur === 'famille' ? 'famille' : 'communauté' }}</span>
                <span class="info-value">{{ optional($demande->demandeur)->nom_structure }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Type de {{ optional($demande->demandeur)->type_detenteur === 'famille' ? 'famille' : 'communauté' }}</span>
                <span class="info-value">{{ optional($demande->demandeur)->type_structure }}</span>
            </div>
            @if(optional($demande->demandeur)->siege_social)
            <div class="info-item">
                <span class="info-label">Siège social</span>
                <span class="info-value">{{ optional($demande->demandeur)->siege_social }}</span>
            </div>
            @endif
            @if(optional($demande->demandeur)->personne_contact)
            <div class="info-item">
                <span class="info-label">Personne de contact</span>
                <span class="info-value">{{ optional($demande->demandeur)->personne_contact }}</span>
            </div>
            @endif
            @endif
            <div class="info-item">
                <span class="info-label">Téléphone</span>
                <span class="info-value">{{ optional($demande->demandeur)->telephone }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Email</span>
                <span class="info-value">{{ optional($demande->demandeur)->email ?: 'Non renseigné' }}</span>
            </div>
            @if(optional($demande->demandeur)->date_naiss)
            <div class="info-item">
                <span class="info-label">Date de naissance</span>
                <span class="info-value">{{ optional($demande->demandeur)->date_naiss->format('d/m/Y') }}</span>
            </div>
            @endif
            @if(optional($demande->demandeur)->lieu_naissance)
            <div class="info-item">
                <span class="info-label">Lieu de naissance</span>
                <span class="info-value">{{ optional($demande->demandeur)->lieu_naissance }}</span>
            </div>
            @endif
            @if(optional($demande->demandeur)->sexe)
            <div class="info-item">
                <span class="info-label">Sexe</span>
                <span class="info-value">{{ optional($demande->demandeur)->sexe === 'M' ? 'Masculin' : 'Féminin' }}</span>
            </div>
            @endif
            @if(optional($demande->demandeur)->groupe_etheroculturel)
            <div class="info-item">
                <span class="info-label">Groupe ethnoculturel</span>
                <span class="info-value">{{ optional($demande->demandeur)->groupe_etheroculturel }}</span>
            </div>
            @endif
            @if(optional($demande->demandeur)->profession)
            <div class="info-item">
                <span class="info-label">Profession</span>
                <span class="info-value">{{ optional($demande->demandeur)->profession }}</span>
            </div>
            @endif
        </div>

        <!-- Demande Info -->
        <div class="info-card">
            <h3 class="card-title">
                <i class="fas fa-info-circle"></i>
                Détails de la Demande
            </h3>
            <div class="info-item">
                <span class="info-label">ID Demande</span>
                <span class="info-value">#{{ $demande->id_demande }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Date de création</span>
                <span class="info-value">{{ optional($demande->created_at)->format('d/m/Y à H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Dernière modification</span>
                <span class="info-value">{{ optional($demande->updated_at)->format('d/m/Y à H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Signature</span>
                <span class="info-value">{{ $demande->signature ? 'Oui' : 'Non' }}</span>
            </div>
        </div>
    </div>

    <!-- Informations Géographiques et Contact -->
    <div class="patrimoines-section">
        <h3 class="card-title">
            <i class="fas fa-map-marker-alt"></i>
            Informations Géographiques et Contact
        </h3>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <div>
                <div class="info-item">
                    <span class="info-label">Province</span>
                    <span class="info-value">{{ optional($demande->demandeur)->province ?: 'Non renseigné' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Commune</span>
                    <span class="info-value">{{ optional($demande->demandeur)->commune ?: 'Non renseigné' }}</span>
                </div>
                @if(optional($demande->demandeur)->coordonnees_geographiques)
                <div class="info-item">
                    <span class="info-label">Coordonnées géographiques</span>
                    <span class="info-value">{{ optional($demande->demandeur)->coordonnees_geographiques }}</span>
                </div>
                @endif
                @if(optional($demande->demandeur)->coordonne_gec)
                <div class="info-item">
                    <span class="info-label">Coordonnées GEC</span>
                    <span class="info-value">{{ optional($demande->demandeur)->coordonne_gec }}</span>
                </div>
                @endif
                @if(optional($demande->demandeur)->deca_element)
                <div class="info-item">
                    <span class="info-label">Décade élément</span>
                    <span class="info-value">{{ optional($demande->demandeur)->deca_element }}</span>
                </div>
                @endif
            </div>
            <div>
                @if(optional($demande->demandeur)->adresse)
                <div class="info-item">
                    <span class="info-label">Adresse</span>
                    <span class="info-value" style="word-break:break-word;">{{ optional($demande->demandeur)->adresse }}</span>
                </div>
                @endif
                @if(optional($demande->demandeur)->groupe_etheroculturel)
                <div class="info-item">
                    <span class="info-label">Groupe ethnoculturel</span>
                    <span class="info-value">{{ optional($demande->demandeur)->groupe_etheroculturel }}</span>
                </div>
                @endif
                @if(optional($demande->demandeur)->profession)
                <div class="info-item">
                    <span class="info-label">Profession</span>
                    <span class="info-value">{{ optional($demande->demandeur)->profession }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Patrimoines Section -->
    <div class="patrimoines-section">
        <h3 class="card-title">
            <i class="fas fa-landmark"></i>
            Éléments Patrimoniaux
        </h3>
        @if($demande->patrimoines->count())
            @foreach($demande->patrimoines as $p)
            <div class="patrimoine-item">
                <div class="patrimoine-code">{{ $p->domaine }}-{{ $p->numero_element }}</div>
                <div>
                    <div class="patrimoine-name">{{ $p->nom }}</div>
                    <div class="patrimoine-domain">{{ $p->domaine }} - {{ optional($p->categorie)->nom_complet }}</div>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Aucun élément patrimonial</h3>
                <p>Aucun élément patrimonial n'est associé à cette demande.</p>
            </div>
        @endif
    </div>

    <!-- Documents Section -->
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

    @if($documentsSpecifiques->count() > 0 || $descriptions->count() > 0 || $piecesJointesOptionnelles->count() > 0)
    <div class="patrimoines-section">
        <h3 class="card-title">
            <i class="fas fa-file-alt"></i>
            Documents enregistrés
        </h3>
        <p style="color:var(--gray); font-size:.9rem; margin-bottom:1.5rem;">
            Documents fournis par le demandeur selon le type de détenteur ({{ ucfirst($demande->demandeur->type_detenteur ?? 'individu') }})
        </p>

        <!-- Documents obligatoires -->
        @if($documentsSpecifiques->count() > 0 || $descriptions->count() > 0)
        <div style="margin-bottom:2rem;">
            <h4 style="font-size:1rem; font-weight:700; color:var(--dark); margin-bottom:1rem; padding-bottom:.5rem; border-bottom:2px solid #e2e8f0;">
                <i class="fas fa-check-circle" style="color:var(--success);"></i> Documents obligatoires
            </h4>
            
            @foreach($documentsSpecifiques as $piece)
                @if($piece->type_piece !== 'txt')
                <div class="document-item">
                    <div class="document-icon" style="background: {{ $piece->mime_type == 'application/pdf' ? '#dc2626' : ($piece->mime_type == 'image/jpeg' || $piece->mime_type == 'image/png' ? '#10b981' : '#667eea') }};">
                        @if($piece->mime_type == 'application/pdf')
                            <i class="fas fa-file-pdf"></i>
                        @elseif(strpos($piece->mime_type, 'image/') === 0)
                            <i class="fas fa-image"></i>
                        @else
                            <i class="fas fa-file"></i>
                        @endif
                    </div>
                    <div class="document-info">
                        <div class="document-name">{{ $piece->description }}</div>
                        <div class="document-meta">
                            <i class="fas fa-file"></i> {{ $piece->nom_fichier }} · 
                            {{ number_format($piece->taille/1024, 1) }} Ko · 
                            {{ $piece->mime_type }}
                        </div>
                        @if(strpos($piece->mime_type, 'image/') === 0)
                        <div style="margin-top:.5rem;">
                            <a href="{{ asset('storage/'.$piece->chemin) }}" target="_blank">
                                <img src="{{ asset('storage/'.$piece->chemin) }}" alt="{{ $piece->description }}" class="document-preview-img">
                            </a>
                        </div>
                        @endif
                    </div>
                    <div>
                        <a href="{{ asset('storage/'.$piece->chemin) }}" target="_blank" class="btn btn-secondary" style="padding:.5rem 1rem; font-size:.85rem;">
                            <i class="fas fa-{{ strpos($piece->mime_type, 'image/') === 0 ? 'eye' : 'download' }}"></i> 
                            {{ strpos($piece->mime_type, 'image/') === 0 ? 'Voir' : 'Télécharger' }}
                        </a>
                    </div>
                </div>
                @endif
            @endforeach

            @foreach($descriptions as $piece)
            <div class="document-item" style="background:#fff8e1;">
                <div class="document-icon" style="background:#f59e0b;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="document-info">
                    <div class="document-name">
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
                    <div class="description-box">
                        {{ $piece->description }}
                    </div>
                    <div class="document-meta" style="margin-top:.5rem;">
                        {{ number_format($piece->taille) }} caractères
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Pièces jointes optionnelles -->
        @if($piecesJointesOptionnelles->count() > 0)
        <div>
            <h4 style="font-size:1rem; font-weight:700; color:var(--dark); margin-bottom:1rem; padding-bottom:.5rem; border-bottom:2px solid #e2e8f0;">
                <i class="fas fa-paperclip" style="color:var(--primary);"></i> Pièces jointes supplémentaires
            </h4>
            @foreach($piecesJointesOptionnelles as $piece)
            <div class="document-item">
                <div class="document-icon" style="background:#64748b;">
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
                <div class="document-info">
                    <div class="document-name">{{ $piece->nom_fichier }}</div>
                    <div class="document-meta">
                        {{ number_format($piece->taille/1024, 1) }} Ko · {{ $piece->mime_type }}
                    </div>
                </div>
                <div>
                    <a href="{{ asset('storage/'.$piece->chemin) }}" target="_blank" class="btn btn-secondary" style="padding:.5rem 1rem; font-size:.85rem;">
                        <i class="fas fa-download"></i> Télécharger
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @else
    <div class="patrimoines-section">
        <h3 class="card-title">
            <i class="fas fa-file-alt"></i>
            Documents enregistrés
        </h3>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucun document</h3>
            <p>Aucun document n'a été fourni pour cette demande.</p>
        </div>
    </div>
    @endif

    <!-- Actions Section -->
    @if($demande->status !== 'valide' && $demande->status !== 'rejete')
    <div class="actions-section">
        <h3 class="card-title">
            <i class="fas fa-cogs"></i>
            Actions de Validation
        </h3>
        <div class="actions-grid">
            <!-- Validation Button -->
            <div class="action-form">
                <button type="button" class="btn btn-success" style="width:100%;" data-bs-toggle="modal" data-bs-target="#validationModal">
                    <i class="fas fa-check"></i> Valider la Demande
                </button>
                <p style="font-size:.85rem; color:var(--gray); text-align:center; margin-top:.5rem;">
                    Approuver cette demande et l'ajouter au répertoire
                </p>
            </div>

            <!-- Rejection Button -->
            <div class="action-form">
                <button type="button" class="btn btn-danger" style="width:100%;" data-bs-toggle="modal" data-bs-target="#rejectionModal">
                    <i class="fas fa-times"></i> Rejeter la Demande
                </button>
                <p style="font-size:.85rem; color:var(--gray); text-align:center; margin-top:.5rem;">
                    Rejeter cette demande avec un motif explicatif
                </p>
            </div>
        </div>
    </div>
    @else
    <div class="actions-section">
        <div style="text-align:center; padding:2rem; color:var(--gray);">
            <i class="fas fa-info-circle" style="font-size:2rem; margin-bottom:1rem; opacity:.5;"></i>
            <h3 style="color:var(--dark); margin-bottom:.5rem;">Demande {{ $demande->status === 'valide' ? 'validée' : 'rejetée' }}</h3>
            <p>Cette demande a déjà été traitée et ne peut plus être modifiée.</p>
        </div>
    </div>
    @endif
</div>

<!-- Modal de Validation -->
<div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--success), #059669); color: white;">
                <h5 class="modal-title" id="validationModalLabel">
                    <i class="fas fa-check-circle me-2"></i>
                    Confirmer la Validation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Attention :</strong> Cette action va valider définitivement la demande #{{ $demande->id_demande }}.
                </div>
                <p>Êtes-vous sûr de vouloir valider cette demande ?</p>
                <div class="demandeur-info" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                    <strong>Demandeur :</strong> {{ optional($demande->demandeur)->nom }} {{ optional($demande->demandeur)->prenom }}<br>
                    <strong>Type :</strong> {{ optional($demande->demandeur)->type_detenteur_formate ?? 'Individu' }}<br>
                    <strong>Éléments patrimoniaux :</strong> {{ $demande->patrimoines->count() }} élément(s)
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <form method="POST" action="{{ route('gestionnaire.demandes.valider', $demande->id_demande) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Confirmer la Validation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rejet -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--danger), #dc2626); color: white;">
                <h5 class="modal-title" id="rejectionModalLabel">
                    <i class="fas fa-times-circle me-2"></i>
                    Confirmer le Rejet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention :</strong> Cette action va rejeter définitivement la demande #{{ $demande->id_demande }}.
                </div>
                <form method="POST" action="{{ route('gestionnaire.demandes.rejeter', $demande->id_demande) }}" id="rejectionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="motif" class="form-label">
                            <strong>Motif du rejet <span class="text-danger">*</span></strong>
                        </label>
                        <textarea class="form-control" id="motif" name="motif" rows="4" 
                                  placeholder="Expliquez clairement le motif du rejet de cette demande..." required></textarea>
                        <div class="form-text">Le motif sera communiqué au demandeur.</div>
                    </div>
                    <div class="demandeur-info" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                        <strong>Demandeur :</strong> {{ optional($demande->demandeur)->nom }} {{ optional($demande->demandeur)->prenom }}<br>
                        <strong>Type :</strong> {{ optional($demande->demandeur)->type_detenteur_formate ?? 'Individu' }}<br>
                        <strong>Éléments patrimoniaux :</strong> {{ $demande->patrimoines->count() }} élément(s)
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('rejectionForm').submit();">
                    <i class="fas fa-times me-2"></i>Confirmer le Rejet
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.info-card, .patrimoines-section, .actions-section');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection









