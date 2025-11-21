@extends('layouts.app')

@section('title', 'Mon Profil')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #FF0000 0%, #00AA00 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .demande-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
        border-left: 4px solid #667eea;
    }

    .demande-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        transform: translateY(-2px);
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

    .btn-action {
        margin-right: 0.5rem;
    }

    .patrimoine-tag {
        display: inline-block;
        background: #f0f0f0;
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        margin: 0.25rem;
        color: #667eea;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <!-- En-tête du profil -->
    <div class="profile-header">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center"
                     style="width: 80px; height: 80px; font-size: 2rem; font-weight: 700;">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </div>
            <div>
                <h2 class="mb-1">{{ $user->name }}</h2>
                <p class="mb-0 opacity-75">
                    <i class="fas fa-envelope"></i> {{ $user->email }}
                </p>
                @if($user->telephone)
                <p class="mb-0 opacity-75">
                    <i class="fas fa-phone"></i> {{ $user->telephone }}
                </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $nombreDemandes }}</div>
                <div class="text-muted">Total des demandes</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $demandesEnAttente }}</div>
                <div class="text-muted">En attente</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card text-center">
                <div class="stat-number" style="color: #28a745;">{{ $demandesValidees }}</div>
                <div class="text-muted">Validées</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card text-center">
                <div class="stat-number" style="color: #dc3545;">{{ $demandesRejetees }}</div>
                <div class="text-muted">Rejetées</div>
            </div>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="card">
        <div class="card-header bg-white">
            <h4 class="mb-0">
                <i class="fas fa-list"></i> Mes Demandes
            </h4>
        </div>
        <div class="card-body">
            @if($demandes->count() > 0)
                @foreach($demandes as $demande)
                    <div class="demande-card">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div class="flex-grow-1 me-3 mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <h5 class="mb-0 me-3">Demande #{{ $demande->id_demande }}</h5>
                                    <span class="status-badge status-{{ str_replace('_', '-', $demande->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $demande->status)) }}
                                    </span>
                                </div>

                                <p class="text-muted mb-2">
                                    <i class="fas fa-calendar"></i> Créée le {{ $demande->created_at->format('d/m/Y à H:i') }}
                                </p>

                                @if($demande->patrimoines->count() > 0)
                                <div class="mb-2">
                                    <strong>Éléments patrimoniaux :</strong>
                                    @foreach($demande->patrimoines as $patrimoine)
                                        <span class="patrimoine-tag">{{ $patrimoine->nom }}</span>
                                    @endforeach
                                </div>
                                @endif

                                @if($demande->type_detenteur)
                                <p class="text-muted mb-0">
                                    <i class="fas fa-user-tag"></i> Type : {{ ucfirst($demande->type_detenteur) }}
                                </p>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                @if($demande->status === 'en_attente')
                                    <a href="{{ route('profil.edit', $demande->id_demande) }}"
                                       class="btn btn-warning btn-sm btn-action">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="{{ route('demande.show', $demande->id_demande) }}"
                                       class="btn btn-primary btn-sm btn-action">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $demande->id_demande }}">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                @else
                                    <a href="{{ route('demande.show', $demande->id_demande) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                    <span class="badge bg-info text-dark">
                                        Modifications non autorisées
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Modal de confirmation de suppression -->
                    <div class="modal fade" id="deleteModal{{ $demande->id_demande }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmer la suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir supprimer cette demande ?</p>
                                    <p class="text-muted">Cette action est irréversible.</p>
                                </div>
                                <div class="modal-footer">
                                    <form method="POST" action="{{ route('profil.destroy', $demande->id_demande) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 4rem; color: #ddd;"></i>
                    <p class="text-muted mt-3">Vous n'avez encore soumis aucune demande.</p>
                    <a href="{{ route('demande.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Créer ma première demande
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

