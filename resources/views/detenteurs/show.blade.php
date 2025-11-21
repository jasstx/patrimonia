@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Détails du Détenteur')

@push('styles')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
        --primary: #dc2626; --secondary: #16a34a; --success: #16a34a;
        --dark: #0f172a; --light: #f8fafc; --gray: #64748b;
    }
    body { background: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    .det-page { min-height: 100vh; padding: 2rem 1rem; }
    .det-container { max-width: 1200px; margin: 0 auto; }

    .page-header { background: white; border-radius: 16px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .page-header h1 { font-size: 2rem; font-weight: 700; color: var(--dark); margin-bottom: .5rem; }
    .page-header p { color: var(--gray); font-size: 1rem; }

    .detenteur-profile { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); margin-bottom: 2rem; }

    .profile-header { position: relative; height: 300px; overflow: hidden; }
    .profile-bg { width: 100%; height: 100%; object-fit: cover; }
    .profile-bg-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display: flex; align-items: center; justify-content: center; }
    .profile-bg-placeholder i { font-size: 6rem; color: rgba(255,255,255,.3); }

    .profile-avatar { position: absolute; bottom: -60px; left: 2rem; width: 120px; height: 120px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 2.5rem; color: var(--primary); border: 6px solid white; box-shadow: 0 8px 24px rgba(0,0,0,.15); }
    .profile-info { padding: 4rem 2rem 2rem; }
    .profile-name { font-size: 2.5rem; font-weight: 800; color: var(--dark); margin-bottom: .5rem; }
    .profile-title { color: var(--gray); font-size: 1.1rem; margin-bottom: 1.5rem; }

    .profile-stats { display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .stat-item { text-align: center; }
    .stat-number { font-size: 2rem; font-weight: 700; color: var(--primary); display: block; }
    .stat-label { color: var(--gray); font-size: .9rem; margin-top: .25rem; }

    .profile-meta { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .meta-item { display: flex; align-items: center; gap: .5rem; color: var(--gray); font-size: .95rem; }
    .meta-item i { color: var(--primary); }

    .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem; }

    .info-card { background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .card-title { font-size: 1.5rem; font-weight: 700; color: var(--dark); margin-bottom: 1.5rem; display: flex; align-items: center; gap: .75rem; }
    .card-title i { color: var(--primary); }

    .patrimoine-item { background: #f8fafc; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; border-left: 4px solid var(--primary); }
    .patrimoine-name { font-size: 1.2rem; font-weight: 600; color: var(--dark); margin-bottom: .5rem; }
    .patrimoine-desc { color: var(--gray); font-size: .95rem; }

    .contact-card { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; border-radius: 16px; padding: 2rem; }
    .contact-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem; }
    .contact-item { display: flex; align-items: center; gap: .75rem; margin-bottom: 1rem; }
    .contact-item i { font-size: 1.2rem; }

    .verification-badge { display: inline-flex; align-items: center; gap: .5rem; padding: .5rem 1rem; background: rgba(22,163,74,.1); color: var(--success); border-radius: 20px; font-weight: 600; font-size: .9rem; }

    .btn-back { padding: .875rem 1.75rem; background: var(--primary); color: white; border: none; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: .5rem; transition: all .2s; cursor: pointer; }
    .btn-back:hover { background: #b91c1c; transform: translateY(-2px); }

    @media (max-width: 768px) {
        .content-grid { grid-template-columns: 1fr; }
        .profile-stats { justify-content: center; }
        .profile-meta { justify-content: center; }
        .profile-avatar { left: 50%; transform: translateX(-50%); }
        .profile-info { text-align: center; padding-top: 5rem; }
    }
</style>
@endpush

@section('content')
<div class="det-page">
    <div class="det-container">
        <div class="page-header">
            <h1><i class="fas fa-user"></i> Détails du Détenteur</h1>
            <p>Informations complètes sur ce gardien du patrimoine culturel</p>
        </div>

       <div class="detenteur-profile">
    <div class="profile-header">
        @php
            // Construction correcte du chemin de la photo
            $photoUrl = $detenteur->photo
                ? asset('storage/' . $detenteur->photo)
                : asset('images/default-avatar.png');

            // Générer les initiales pour l'avatar
            $initials = strtoupper(
                (optional($detenteur->demandeur)->prenom ? mb_substr(optional($detenteur->demandeur)->prenom, 0, 1) : '') .
                (optional($detenteur->demandeur)->nom ? mb_substr(optional($detenteur->demandeur)->nom, 0, 1) : '')
            );
        @endphp

        <img src="{{ $photoUrl }}"
             alt="Photo de {{ optional($detenteur->demandeur)->nom }}"
             class="profile-bg"
             onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">

        {{-- Afficher les initiales --}}
        <div class="profile-avatar">{{ $initials ?: '?' }}</div>
    </div>
            <div class="profile-info">
                <h1 class="profile-name">{{ optional($detenteur->demandeur)->nom }} {{ optional($detenteur->demandeur)->prenom }}</h1>
                <p class="profile-title">{{ ucfirst($detenteur->type_detenteur) }} • Détenteur #{{ $detenteur->id_detenteur }}</p>

                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $detenteur->patrimoines->count() }}</span>
                        <span class="stat-label">Patrimoines</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $detenteur->annees_experience ?? 'N/A' }}</span>
                        <span class="stat-label">Années d'expérience</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">4.{{ rand(5,9) }}</span>
                        <span class="stat-label">Note</span>
                    </div>
                </div>

                <div class="profile-meta">
                    @if(optional($detenteur->demandeur)->ville)
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ optional($detenteur->demandeur)->ville }}</span>
                    </div>
                    @endif
                    @if(optional($detenteur->demandeur)->telephone)
                    <div class="meta-item">
                        <i class="fas fa-phone"></i>
                        <span>{{ optional($detenteur->demandeur)->telephone }}</span>
                    </div>
                    @endif
                    @if(optional($detenteur->demandeur)->email)
                    <div class="meta-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ optional($detenteur->demandeur)->email }}</span>
                    </div>
                    @endif
                    <div class="meta-item">
                        <i class="fas fa-check-circle"></i>
                        <span class="verification-badge">
                            <i class="fas fa-shield-alt"></i>
                            Vérifié le {{ optional($detenteur->date_verification)->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="info-card">
                <h2 class="card-title">
                    <i class="fas fa-landmark"></i>
                    Éléments du Patrimoine
                </h2>
                @forelse($detenteur->patrimoines as $patrimoine)
                <div class="patrimoine-item">
                    <h3 class="patrimoine-name">{{ $patrimoine->nom }}</h3>
                    <p class="patrimoine-desc">
                        <strong>Domaine:</strong> {{ $patrimoine->domaine }}<br>
                        <strong>Type:</strong> {{ $patrimoine->type_element }}<br>
                        @if($patrimoine->description)
                        <strong>Description:</strong> {{ $patrimoine->description }}
                        @endif
                    </p>
                </div>
                @empty
                <p style="color: var(--gray); text-align: center; padding: 2rem;">
                    <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.5;"></i><br>
                    Aucun élément patrimonial enregistré.
                </p>
                @endforelse
            </div>

            <div class="contact-card">
                <h3 class="contact-title">
                    <i class="fas fa-address-book"></i>
                    Informations de Contact
                </h3>

                @if(optional($detenteur->demandeur)->telephone)
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ optional($detenteur->demandeur)->telephone }}</span>
                </div>
                @endif

                @if(optional($detenteur->demandeur)->email)
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ optional($detenteur->demandeur)->email }}</span>
                </div>
                @endif

                @if(optional($detenteur->demandeur)->adresse)
                <div class="contact-item">
                    <i class="fas fa-home"></i>
                    <span>{{ optional($detenteur->demandeur)->adresse }}</span>
                </div>
                @endif

                @if(optional($detenteur->demandeur)->ville)
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ optional($detenteur->demandeur)->ville }}</span>
                </div>
                @endif

                @if($detenteur->biographie)
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,.2);">
                    <h4 style="margin-bottom: 1rem; font-size: 1.1rem;">Biographie</h4>
                    <p style="opacity: .9; line-height: 1.6;">{{ $detenteur->biographie }}</p>
                </div>
                @endif
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('detenteurs.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste des détenteurs
            </a>
        </div>
    </div>
</div>
@endsection


