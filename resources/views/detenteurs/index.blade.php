@extends('layouts.app')

@section('title', 'Liste des Détenteurs')

@push('styles')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
        --primary: #dc2626; --secondary: #16a34a; --success: #16a34a;
        --dark: #0f172a; --light: #f8fafc; --gray: #64748b;
    }
    body { background: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    .det-page { min-height: 100vh; padding: 2rem 1rem; }
    .det-container { max-width: 1400px; margin: 0 auto; }

    .page-header { background: white; border-radius: 16px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .page-header h1 { font-size: 2rem; font-weight: 700; color: var(--dark); margin-bottom: .5rem; }
    .page-header p { color: var(--gray); font-size: 1rem; }

    .stats-bar { display: flex; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .stat-item { background: white; border-radius: 12px; padding: 1.25rem 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); flex: 1; min-width: 180px; }
    .stat-item .number { font-size: 2rem; font-weight: 700; color: var(--primary); }
    .stat-item .label { color: var(--gray); font-size: .875rem; margin-top: .25rem; }

    .search-section { background: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .search-box { position: relative; max-width: 500px; }
    .search-box input { width: 100%; padding: .875rem 1rem .875rem 2.75rem; border: 2px solid #e2e8f0; border-radius: 10px; font-size: .95rem; transition: border .2s; }
    .search-box input:focus { outline: none; border-color: var(--primary); }
    .search-box i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray); }

    .detenteurs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(480px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }

    .detenteur-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); transition: all .3s ease; display: flex; height: 200px; }
    .detenteur-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
    .detenteur-card:hover .card-title { color: var(--primary); }

    .card-image { width: 280px; position: relative; overflow: hidden; flex-shrink: 0; }
    .card-image img { width: 100%; height: 100%; object-fit: cover; }
    .card-image-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display: flex; align-items: center; justify-content: center; }
    .card-image-placeholder i { font-size: 4rem; color: rgba(255,255,255,.3); }
    .card-avatar { position: absolute; bottom: 1rem; left: 1rem; width: 60px; height: 60px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.3rem; color: var(--primary); border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,.15); }
    .favorite-btn { position: absolute; top: 1rem; right: 1rem; width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,.15); transition: all .2s; z-index: 10; }
    .favorite-btn:hover { transform: scale(1.1); }
    .favorite-btn i { color: var(--gray); font-size: 1.1rem; }

    .view-btn { position: absolute; top: 1rem; right: 4rem; width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,.15); transition: all .2s; z-index: 10; }
    .view-btn:hover { transform: scale(1.1); background: #b91c1c; }
    .view-btn i { color: white; font-size: 1.1rem; }

    /* Modal Styles */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: white; margin: 2% auto; padding: 0; border-radius: 20px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: modalSlideIn 0.3s ease-out; }
    @keyframes modalSlideIn { from { opacity: 0; transform: translateY(-50px); } to { opacity: 1; transform: translateY(0); } }

    .modal-header { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 2rem; border-radius: 20px 20px 0 0; position: relative; }
    .modal-header h2 { margin: 0; font-size: 1.8rem; font-weight: 700; }
    .modal-header p { margin: 0.5rem 0 0 0; opacity: 0.9; }
    .close { position: absolute; top: 1rem; right: 1.5rem; color: white; font-size: 2rem; font-weight: bold; cursor: pointer; transition: all 0.2s; }
    .close:hover { transform: scale(1.1); }

    .modal-body { padding: 0; }

    /* Section principale avec photo et infos de base */
    .detenteur-profile { 
        display: grid; 
        grid-template-columns: 220px 1fr; 
        gap: 2.5rem; 
        padding: 2.5rem; 
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); 
        border-radius: 0 0 20px 20px; 
        align-items: center;
    }
    .detenteur-photo { text-align: center; }
    .detenteur-photo img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }
    .detenteur-photo-placeholder { width: 150px; height: 150px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 8px 24px rgba(0,0,0,0.15); }
    .detenteur-photo-placeholder i { font-size: 4rem; color: rgba(255,255,255,0.7); }

    .detenteur-basic-info h3 { color: var(--dark); font-size: 1.8rem; margin-bottom: 0.5rem; font-weight: 800; }
    .detenteur-basic-info .type-badge { display: inline-block; padding: 0.25rem 0.75rem; background: var(--primary); color: white; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-bottom: 1rem; }
    .detenteur-basic-info .verification-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: rgba(22,163,74,0.1); color: var(--success); border-radius: 20px; font-weight: 600; font-size: 0.9rem; margin-bottom: 1rem; }

    /* Grille d'informations */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; padding: 2rem; }
    .info-section { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-left: 4px solid var(--primary); }
    .info-section h4 { color: var(--dark); font-size: 1.2rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 700; }
    .info-section h4 i { color: var(--primary); }

    .detail-item { display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem; color: var(--gray); }
    .detail-item i { color: var(--primary); width: 20px; margin-top: 0.2rem; flex-shrink: 0; }
    .detail-item .label { font-weight: 600; color: var(--dark); min-width: 120px; }
    .detail-item .value { flex: 1; color: var(--dark); }
    .detail-item .value:empty::after { content: "Non renseigné"; color: var(--gray); font-style: italic; }

    /* Section patrimoines */
    .patrimoines-section { padding: 2rem; background: #f8fafc; }
    .patrimoines-section h4 { color: var(--dark); font-size: 1.4rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 700; }
    .patrimoines-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem; }
    .patrimoine-item { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-left: 4px solid var(--secondary); transition: all 0.3s ease; }
    .patrimoine-item:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
    .patrimoine-name { font-weight: 700; color: var(--dark); margin-bottom: 0.75rem; font-size: 1.1rem; }
    .patrimoine-desc { color: var(--gray); font-size: 0.9rem; line-height: 1.5; }
    .patrimoine-desc .field { margin-bottom: 0.5rem; }
    .patrimoine-desc .field strong { color: var(--dark); }

    /* Statistiques */
    .stats-row { display: flex; gap: 1rem; margin-bottom: 1rem; }
    .stat-badge { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; }

    /* Responsive */
    @media (max-width: 768px) {
        .detenteur-profile { grid-template-columns: 1fr; text-align: center; }
        .info-grid { grid-template-columns: 1fr; }
        .patrimoines-grid { grid-template-columns: 1fr; }
        .stats-row { flex-direction: column; }
    }

    @media (max-width: 768px) {
        .modal-content { width: 95%; margin: 5% auto; }
        .detenteur-info { grid-template-columns: 1fr; text-align: center; }
    }

    .card-content { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; }
    .card-number { color: var(--primary); font-weight: 700; font-size: .875rem; margin-bottom: .5rem; }
    .card-title { font-size: 1.25rem; font-weight: 700; color: var(--dark); margin-bottom: .5rem; line-height: 1.3; }
    .card-rating { display: flex; align-items: center; gap: .5rem; margin-bottom: .75rem; }
    .rating-value { font-weight: 700; color: var(--dark); }
    .rating-stars { color: var(--success); }
    .reviews-link { color: var(--gray); font-size: .875rem; text-decoration: underline; }
    .card-meta { display: flex; align-items: center; gap: .5rem; color: var(--gray); font-size: .875rem; margin-bottom: 1rem; flex-wrap: wrap; }
    .card-meta i { color: var(--primary); }
    .card-meta span::after { content: "•"; margin-left: .5rem; }
    .card-meta span:last-child::after { content: ""; margin-left: 0; }

    .card-tags { display: flex; flex-wrap: wrap; gap: .5rem; margin-bottom: auto; }
    .tag { padding: .375rem .75rem; background: #f1f5f9; border-radius: 6px; font-size: .8rem; color: var(--gray); font-weight: 500; }

    .card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }
    .card-price { text-align: right; }
    .price-label { color: var(--gray); font-size: .75rem; }
    .price-value { font-size: 1rem; font-weight: 700; color: var(--success); }
    .btn-view-details {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white !important;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-view-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        color: white !important;
        text-decoration: none !important;
    }

    .footer-bar { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,.06); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
    .footer-info { color: var(--gray); }
    .btn-back { padding: .875rem 1.75rem; background: var(--primary); color: white; border: none; border-radius: 10px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: .5rem; transition: all .2s; cursor: pointer; }
    .btn-back:hover { background: #2563eb; transform: translateY(-2px); }

    @media (max-width: 768px) {
        .detenteurs-grid { grid-template-columns: 1fr; }
        .detenteur-card { flex-direction: column; height: auto; }
        .card-image { width: 100%; height: 180px; }
        .stats-bar { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="det-page">
    <div class="det-container">
        <div class="page-header">
            <h1><i class="fas fa-users"></i> Détenteurs de Patrimoine</h1>
            <p>Répertoire officiel des gardiens de notre héritage culturel</p>
        </div>

       <!-- <div class="stats-bar">
            <div class="stat-item">
                <div class="number">{{ $detenteurs->count() }}</div>
                <div class="label">Détenteurs Vérifiés</div>
            </div>
            <div class="stat-item">
                <div class="number">{{ $detenteurs->flatMap(fn($d) => $d->patrimoines)->unique('id')->count() }}</div>
                <div class="label">Éléments Patrimoniaux</div>
            </div>
            <div class="stat-item">
                <div class="number">{{ $detenteurs->pluck('demandeur.ville')->filter()->unique()->count() }}</div>
                <div class="label">Villes Représentées</div>
            </div>
        </div> -->

        <div class="search-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Rechercher un détenteur par nom, ville, type...">
            </div>
        </div>

        <div class="detenteurs-grid" id="detenteursGrid">
            @forelse($detenteurs as $d)
            <div class="detenteur-card" data-searchable="{{ strtolower(optional($d->demandeur)->nom.' '.optional($d->demandeur)->prenom.' '.optional($d->demandeur)->ville.' '.$d->type_detenteur) }}" onclick="openDetenteurModal({{ $d->id_detenteur }})" style="cursor: pointer;">
                <div class="card-image">
                    @php
                        $hasPhoto = !empty($d->photo);
                        $photoUrl = $d->photo_url;
                    @endphp
                    @if($hasPhoto)
                        <img src="{{ $photoUrl }}"
                             alt="Photo de {{ optional($d->demandeur)->nom }} {{ optional($d->demandeur)->prenom }}"
                             loading="lazy"
                             style="width: 100%; height: 100%; object-fit: cover;"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}'">
                    @else
                        <div class="card-image-placeholder" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    @php $initials = strtoupper(mb_substr(optional($d->demandeur)->prenom,0,1).mb_substr(optional($d->demandeur)->nom,0,1)); @endphp
                    <div class="card-avatar">{{ $initials }}</div>
                    <div class="favorite-btn">
                        <i class="far fa-heart"></i>
                    </div>
                    <div class="view-btn" title="Voir les détails" onclick="event.stopPropagation(); openDetenteurModal({{ $d->id_detenteur }})">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>

                <div class="card-content">
                    <div class="card-number">{{ $loop->iteration }}. Détenteur #{{ $d->id_detenteur }}</div>
                    <h3 class="card-title">{{ optional($d->demandeur)->nom }} {{ optional($d->demandeur)->prenom }}</h3>

                    <div class="card-rating">
                        <span class="rating-value">4.{{ rand(5,9) }}</span>
                        <span class="rating-stars">
                            <i class="fas fa-circle"></i>
                            <i class="fas fa-circle"></i>
                            <i class="fas fa-circle"></i>
                            <i class="fas fa-circle"></i>
                            <i class="fas fa-circle"></i>
                        </span>
                        <a href="#" class="reviews-link">({{ $d->patrimoines->count() }} patrimoine(s))</a>
                    </div>

                    <div class="card-meta">
                        <span><i class="fas fa-tag"></i> {{ $d->type_detenteur }}</span>
                        @if(optional($d->demandeur)->ville)
                        <span><i class="fas fa-map-marker-alt"></i> {{ optional($d->demandeur)->ville }}</span>
                        @endif
                        @if(optional($d->demandeur)->telephone)
                        <span><i class="fas fa-phone"></i> {{ optional($d->demandeur)->telephone }}</span>
                        @endif
                    </div>

                    <div class="card-tags">
                        @foreach($d->patrimoines->take(2) as $p)
                            <span class="tag">{{ $p->nom }}</span>
                        @endforeach
                        @if($d->patrimoines->count() > 2)
                            <span class="tag">+{{ $d->patrimoines->count() - 2 }}</span>
                        @endif
                    </div>

                    <div class="card-footer">
                        <button class="btn-view-details" onclick="event.stopPropagation(); openDetenteurModal({{ $d->id_detenteur }})">
                            <i class="fas fa-eye"></i> Voir détails
                        </button>
                        <div class="card-price">
                            <div class="price-label">Vérifié le</div>
                            <div class="price-value">
                                <i class="fas fa-check-circle"></i> {{ optional($d->date_verification)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="detenteur-card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <div style="color: var(--gray); font-size: 1.1rem;">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p>Aucun détenteur vérifié pour le moment.</p>
                </div>
            </div>
            @endforelse
        </div>

        <div class="footer-bar">
            <div class="footer-info">
                <i class="fas fa-info-circle"></i>
                <strong id="countVisible">{{ $detenteurs->count() }}</strong> détenteur(s) affiché(s)
            </div>
            <a href="{{ route('home') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails du détenteur -->
<div id="detenteurModal" class="modal" style="display: none;">
    <div class="modal-content" style="margin: 2% auto; max-width: 900px; width: 90%; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 50px rgba(0,0,0,0.2);">
        <div class="modal-header" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 1.5rem 2rem; position: relative;">
            <div>
                <h2 id="modalTitle" style="margin: 0; font-size: 1.75rem; font-weight: 700;">Chargement...</h2>
                <p id="modalSubtitle" style="margin: 0.5rem 0 0; opacity: 0.9; font-size: 1rem;"></p>
            </div>
            <button class="close" onclick="closeDetenteurModal()" style="position: absolute; top: 1.5rem; right: 1.5rem; background: rgba(255,255,255,0.2); border: none; width: 36px; height: 36px; border-radius: 50%; color: white; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                &times;
            </button>
        </div>
        <div class="modal-body" id="modalBody" style="max-height: 70vh; overflow-y: auto; padding: 0;">
            <div style="text-align: center; padding: 3rem 2rem;">
                <div class="spinner" style="width: 50px; height: 50px; margin: 0 auto 1rem; border: 4px solid rgba(0,0,0,0.1); border-left-color: var(--primary); border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="margin-top: 1rem; color: var(--dark); font-size: 1.1rem;">Chargement des détails du détenteur...</p>
            </div>
        </div>
        <div class="modal-footer" style="padding: 1.5rem; display: flex; justify-content: flex-end; border-top: 1px solid #e2e8f0;">
            <button type="button" class="btn btn-outline-secondary" style="padding: 0.6rem 1.2rem; border-radius: 8px; border: 1px solid #e2e8f0; background: white; color: #4a5568; cursor: pointer; transition: all 0.2s;" onclick="closeDetenteurModal()">
                <i class="fas fa-times mr-2"></i> Fermer
            </button>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}

.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    transition: opacity 0.3s ease;
}

.modal-content {
    background: white;
    margin: 2% auto;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
}

.close:hover {
    background: rgba(255,255,255,0.3) !important;
    transform: rotate(90deg);
}
</style>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const grid = document.getElementById('detenteursGrid');
    const countVisible = document.getElementById('countVisible');

    if (searchInput && grid) {
        searchInput.addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            let visible = 0;

            grid.querySelectorAll('.detenteur-card').forEach(card => {
                const searchText = card.getAttribute('data-searchable');
                const show = searchText.includes(value);
                card.style.display = show ? 'flex' : 'none';
                if (show) visible += 1;
            });

            if (countVisible) countVisible.textContent = visible;
        });
    }

    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#ef4444';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                icon.style.color = '';
            }
        });
    });


    window.addEventListener('load', () => {
        const cards = document.querySelectorAll('.detenteur-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 80);
        });
    });

    // Fonctions pour la modal
    function openDetenteurModal(detenteurId, event = null) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const modal = document.getElementById('detenteurModal');
        const modalBody = document.getElementById('modalBody');
        const modalTitle = document.getElementById('modalTitle');
        const modalSubtitle = document.getElementById('modalSubtitle');
        
        // Afficher le modal avec une animation fluide
        modal.style.display = 'flex';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        document.body.style.overflow = 'hidden';
        
        // Afficher un indicateur de chargement élégant
        modalBody.innerHTML = `
            <div style="text-align: center; padding: 3rem 2rem;">
                <div class="spinner" style="width: 50px; height: 50px; margin: 0 auto 1rem; border: 4px solid rgba(0,0,0,0.1); border-left-color: var(--primary); border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="margin-top: 1rem; color: var(--dark); font-size: 1.1rem;">Chargement des détails du détenteur...</p>
            </div>
        `;
        
        // Récupérer les données du détenteur via une requête AJAX
        fetch(`/api/detenteurs/${detenteurId}`)
            .then(response => response.json())
            .then(detenteur => {
                // Mettre à jour le titre et le sous-titre
                const nomComplet = `${detenteur.demandeur?.nom || ''} ${detenteur.demandeur?.prenom || ''}`.trim() || 'Détenteur sans nom';
                modalTitle.textContent = nomComplet;
                modalSubtitle.textContent = `${detenteur.type_detenteur || 'Détenteur'} • #${detenteur.id_detenteur}`;

                // Générer le contenu de la modal avec une photo améliorée
                const initials = (detenteur.demandeur?.prenom?.charAt(0) || '') + (detenteur.demandeur?.nom?.charAt(0) || '');
                const photoUrl = detenteur.photo ? `/storage/${detenteur.photo}` : '';
                const photoHtml = detenteur.photo ?
                    `<div class="relative group" style="width: 180px; height: 180px; margin: 0 auto; cursor: pointer;" onclick="openFullscreenImage('${photoUrl}')">
                        <img src="${photoUrl}" 
                             alt="Photo de ${detenteur.demandeur?.prenom || ''} ${detenteur.demandeur?.nom || ''}" 
                             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; 
                                    border: 4px solid var(--primary); box-shadow: 0 8px 24px rgba(0,0,0,0.15);
                                    transition: all 0.3s ease;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             class="group-hover:opacity-90 group-hover:shadow-xl group-hover:border-secondary">
                        <div class="absolute inset-0 bg-black bg-opacity-30 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="text-white bg-black bg-opacity-50 rounded-full p-2">
                                <i class="fas fa-expand"></i> Agrandir
                            </span>
                        </div>
                    </div>` :
                    `<div class="detenteur-photo-placeholder" style="width: 180px; height: 180px; margin: 0 auto; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%;">
                        <i class="fas fa-user" style="font-size: 4rem; color: rgba(255,255,255,0.7);"></i>
                    </div>`;

                const patrimoinesHtml = detenteur.patrimoines && detenteur.patrimoines.length > 0 ?
                    detenteur.patrimoines.map(patrimoine => {
                        const pivot = patrimoine.pivot || {};
                        return `
                        <div class="patrimoine-item">
                            <div class="patrimoine-name">${patrimoine.nom}</div>
                            <div class="patrimoine-desc">
                                <div class="field"><strong>Domaine:</strong> ${patrimoine.domaine || 'Non spécifié'}</div>
                                ${patrimoine.initiale ? `<div class="field"><strong>Initiale:</strong> ${patrimoine.initiale}</div>` : ''}
                                ${patrimoine.numero_element ? `<div class="field"><strong>Numéro:</strong> ${patrimoine.numero_element}</div>` : ''}
                                ${patrimoine.categorie ? `<div class="field"><strong>Catégorie:</strong> ${patrimoine.categorie.nom || patrimoine.categorie}</div>` : ''}
                                ${patrimoine.localisation ? `<div class="field"><strong>Localisation:</strong> ${patrimoine.localisation}</div>` : ''}
                                ${patrimoine.region ? `<div class="field"><strong>Région:</strong> ${patrimoine.region}</div>` : ''}
                                ${patrimoine.status ? `<div class="field"><strong>Statut:</strong> ${patrimoine.status}</div>` : ''}
                                ${patrimoine.date_inscription ? `<div class="field"><strong>Date d'inscription:</strong> ${new Date(patrimoine.date_inscription).toLocaleDateString('fr-FR')}</div>` : ''}
                                ${patrimoine.historique ? `<div class="field"><strong>Historique:</strong> ${patrimoine.historique}</div>` : ''}
                                ${patrimoine.caracteristiques ? `<div class="field"><strong>Caractéristiques:</strong> ${patrimoine.caracteristiques}</div>` : ''}
                                ${patrimoine.est_urgent ? `<div class="field"><strong>Urgent:</strong> <span style="color: var(--danger);">Oui</span></div>` : ''}
                                ${patrimoine.description ? `<div class="field"><strong>Description:</strong> ${patrimoine.description}</div>` : ''}

                                <!-- Informations de détention -->
                                ${pivot.date_debut_detention ? `<div class="field" style="border-top: 1px solid #e2e8f0; padding-top: 0.5rem; margin-top: 0.5rem;"><strong>Date de début de détention:</strong> ${new Date(pivot.date_debut_detention).toLocaleDateString('fr-FR')}</div>` : ''}
                                ${pivot.type_detention ? `<div class="field"><strong>Type de détention:</strong> ${pivot.type_detention}</div>` : ''}
                                ${pivot.preuves ? `<div class="field"><strong>Preuves:</strong> ${pivot.preuves}</div>` : ''}
                                ${pivot.est_actif !== undefined ? `<div class="field"><strong>Actif:</strong> <span style="color: ${pivot.est_actif ? 'var(--success)' : 'var(--danger)'};">${pivot.est_actif ? 'Oui' : 'Non'}</span></div>` : ''}
                            </div>
                        </div>
                    `;
                    }).join('') :
                    '<p style="text-align: center; color: var(--gray); padding: 2rem;">Aucun élément patrimonial enregistré.</p>';

                modalBody.innerHTML = `
                    <!-- Section profil principal -->
                    <div class="detenteur-profile">
                        <div class="detenteur-photo">
                            ${photoHtml}
                            <div class="detenteur-photo-placeholder" style="display: none;"><i class="fas fa-user"></i></div>
                        </div>
                        <div class="detenteur-basic-info">
                            <h3>${detenteur.demandeur?.nom || ''} ${detenteur.demandeur?.prenom || ''}</h3>
                            <div class="type-badge">${detenteur.type_detenteur}</div>
                            <div class="verification-badge">
                                <i class="fas fa-shield-alt"></i>
                                Vérifié le ${new Date(detenteur.date_verification).toLocaleDateString('fr-FR')}
                            </div>
                            <div class="stats-row">
                                <div class="stat-badge">
                                    <i class="fas fa-landmark"></i> ${detenteur.patrimoines?.length || 0} Patrimoine(s)
                                </div>
                                ${detenteur.annees_experience ? `<div class="stat-badge"><i class="fas fa-calendar"></i> ${detenteur.annees_experience} ans d'expérience</div>` : ''}
                            </div>
                        </div>
                    </div>

                    <!-- Grille d'informations détaillées -->
                    <div class="info-grid">
                        <!-- Informations personnelles -->
                        <div class="info-section">
                            <h4><i class="fas fa-user"></i> Informations Personnelles</h4>
                            <div class="detail-item">
                                <i class="fas fa-birthday-cake"></i>
                                <span class="label">Date de naissance:</span>
                                <span class="value">${detenteur.demandeur?.date_naiss ? new Date(detenteur.demandeur.date_naiss).toLocaleDateString('fr-FR') : 'Non renseigné'}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-map-pin"></i>
                                <span class="label">Lieu de naissance:</span>
                                <span class="value">${detenteur.demandeur?.lieu_naissance || 'Non renseigné'}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-venus-mars"></i>
                                <span class="label">Sexe:</span>
                                <span class="value">${detenteur.demandeur?.sexe ? (detenteur.demandeur.sexe === 'M' ? 'Masculin' : 'Féminin') : 'Non renseigné'}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <span class="label">Groupe ethnoculturel:</span>
                                <span class="value">${detenteur.demandeur?.groupe_etheroculturel || 'Non renseigné'}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-briefcase"></i>
                                <span class="label">Profession:</span>
                                <span class="value">${detenteur.demandeur?.profession || 'Non renseigné'}</span>
                            </div>
                        </div>

                        <!-- Informations de contact -->
                        <div class="info-section">
                            <h4><i class="fas fa-address-book"></i> Contact</h4>
                            <div class="detail-item">
                                <i class="fas fa-phone"></i>
                                <span class="label">Téléphone:</span>
                                <span class="value">${detenteur.demandeur?.telephone || 'Non renseigné'}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <span class="label">Email:</span>
                                <span class="value">${detenteur.demandeur?.email || 'Non renseigné'}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-home"></i>
                                <span class="label">Adresse:</span>
                                <span class="value">${detenteur.demandeur?.adresse || 'Non renseigné'}</span>
                            </div>
                        </div>

                        <!-- Informations géographiques -->
                        <div class="info-section">
                            <h4><i class="fas fa-map-marker-alt"></i> Informations Géographiques</h4>
                            <div class="detail-item">
                                <i class="fas fa-map"></i>
                                <span class="label">Province:</span>
                                <span class="value">${detenteur.demandeur?.province || 'Non renseigné'}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-building"></i>
                                <span class="label">Commune:</span>
                                <span class="value">${detenteur.demandeur?.commune || 'Non renseigné'}</span>
                            </div>
                            ${detenteur.demandeur?.coordonnees_geographiques ? `<div class="detail-item"><i class="fas fa-globe"></i><span class="label">Coordonnées géographiques:</span><span class="value">${detenteur.demandeur.coordonnees_geographiques}</span></div>` : ''}
                        </div>

                        <!-- Informations de structure (si communauté) -->
                        ${detenteur.demandeur?.type_detenteur === 'communaute' || detenteur.demandeur?.type_detenteur === 'famille' ? `
                        <div class="info-section">
                            <h4><i class="fas fa-building"></i> Informations de Structure</h4>
                            ${detenteur.demandeur?.nom_structure ? `<div class="detail-item"><i class="fas fa-building"></i><span class="label">Nom de la structure:</span><span class="value">${detenteur.demandeur.nom_structure}</span></div>` : ''}
                            ${detenteur.demandeur?.type_structure ? `<div class="detail-item"><i class="fas fa-sitemap"></i><span class="label">Type de structure:</span><span class="value">${detenteur.demandeur.type_structure}</span></div>` : ''}
                            ${detenteur.demandeur?.siege_social ? `<div class="detail-item"><i class="fas fa-map-marker-alt"></i><span class="label">Siège social:</span><span class="value">${detenteur.demandeur.siege_social}</span></div>` : ''}
                            ${detenteur.demandeur?.personne_contact ? `<div class="detail-item"><i class="fas fa-user-tie"></i><span class="label">Personne de contact:</span><span class="value">${detenteur.demandeur.personne_contact}</span></div>` : ''}
                        </div>
                        ` : ''}

                        <!-- Informations de vérification -->
                        <div class="info-section">
                            <h4><i class="fas fa-shield-alt"></i> Vérification</h4>
                            <div class="detail-item"><i class="fas fa-check-circle"></i><span class="label">Statut:</span><span class="value">Vérifié</span></div>
                            <div class="detail-item"><i class="fas fa-calendar-check"></i><span class="label">Date de vérification:</span><span class="value">${new Date(detenteur.date_verification).toLocaleDateString('fr-FR')}</span></div>
                            ${detenteur.verificateur ? `<div class="detail-item"><i class="fas fa-user-check"></i><span class="label">Vérifié par:</span><span class="value">${detenteur.verificateur.name || 'Administrateur'}</span></div>` : ''}
                            <div class="detail-item"><i class="fas fa-id-card"></i><span class="label">ID Détenteur:</span><span class="value">#${detenteur.id_detenteur}</span></div>
                        </div>
                    </div>

                    <!-- Section patrimoines -->
                    <div class="patrimoines-section">
                        <h4><i class="fas fa-landmark"></i> Éléments du Patrimoine (${detenteur.patrimoines?.length || 0})</h4>
                        <div class="patrimoines-grid">
                            ${patrimoinesHtml}
                        </div>
                    </div>

                    <!-- Informations de la demande -->
                    ${detenteur.demandeur?.demandes && detenteur.demandeur.demandes.length > 0 ? `
                    <div class="info-grid">
                        <div class="info-section" style="grid-column: 1 / -1;">
                            <h4><i class="fas fa-file-alt"></i> Informations de la Demande</h4>
                            ${detenteur.demandeur.demandes.map(demande => `
                                <div style="background: #f8fafc; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; border-left: 4px solid var(--primary);">
                                    <div class="detail-item"><i class="fas fa-hashtag"></i><span class="label">ID Demande:</span><span class="value">#${demande.id_demande}</span></div>
                                    <div class="detail-item"><i class="fas fa-calendar"></i><span class="label">Date de création:</span><span class="value">${new Date(demande.date_creation).toLocaleDateString('fr-FR')}</span></div>
                                    <div class="detail-item"><i class="fas fa-info-circle"></i><span class="label">Type:</span><span class="value">${demande.type_demande}</span></div>
                                    <div class="detail-item"><i class="fas fa-check-circle"></i><span class="label">Statut:</span><span class="value" style="color: ${demande.status === 'validee' ? 'var(--success)' : demande.status === 'rejetee' ? 'var(--danger)' : 'var(--warning)'};">${demande.status}</span></div>
                                    ${demande.validee_le ? `<div class="detail-item"><i class="fas fa-calendar-check"></i><span class="label">Validée le:</span><span class="value">${new Date(demande.validee_le).toLocaleDateString('fr-FR')}</span></div>` : ''}
                                    ${demande.declaration_honneur ? `<div class="detail-item"><i class="fas fa-handshake"></i><span class="label">Déclaration d'honneur:</span><span class="value" style="color: var(--success);">Signée</span></div>` : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    ` : ''}

                    <!-- Biographie si disponible -->
                    ${detenteur.biographie ? `
                    <div class="info-grid">
                        <div class="info-section" style="grid-column: 1 / -1;">
                            <h4><i class="fas fa-book-open"></i> Biographie</h4>
                            <p style="color: var(--gray); line-height: 1.6; margin: 0;">${detenteur.biographie}</p>
                        </div>
                    </div>
                    ` : ''}
                `;
            })
            .catch(error => {
                console.error('Erreur:', error);
                modalBody.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--danger);"><i class="fas fa-exclamation-triangle" style="font-size: 2rem;"></i><p style="margin-top: 1rem;">Erreur lors du chargement des données</p></div>';
            });
    }

    function closeDetenteurModal() {
        const modal = document.getElementById('detenteurModal');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            modal.style.opacity = '1';
        }, 200);
    }

    // Fermer le modal en cliquant en dehors ou avec la touche Échap
    window.onclick = function(event) {
        const modal = document.getElementById('detenteurModal');
        if (event.target == modal) {
            closeDetenteurModal();
        }
    };

    // Fermer avec la touche Échap
    document.addEventListener('keydown', function(event) {
        const modal = document.getElementById('detenteurModal');
        const fullscreenImg = document.getElementById('fullscreenImage');
        
        if (event.key === 'Escape') {
            if (modal && modal.style.display === 'flex') {
                closeDetenteurModal();
            }
            if (fullscreenImg && fullscreenImg.style.display === 'flex') {
                closeFullscreenImage();
            }
        }
    });
    
    // Fonction pour afficher l'image en plein écran
    function openFullscreenImage(imageUrl) {
        const fullscreenDiv = document.createElement('div');
        fullscreenDiv.id = 'fullscreenImage';
        fullscreenDiv.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            cursor: zoom-out;
            padding: 2rem;
        `;
        
        fullscreenDiv.innerHTML = `
            <div style="position: relative; max-width: 90%; max-height: 90%;">
                <img src="${imageUrl}" 
                     alt="Photo du détenteur" 
                     style="max-width: 100%; max-height: 90vh; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                <button onclick="closeFullscreenImage()" 
                        style="position: absolute; top: -40px; right: -10px; background: var(--primary); color: white; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    &times;
                </button>
                <div style="position: absolute; bottom: -40px; left: 0; right: 0; text-align: center; color: white; font-size: 0.9rem; opacity: 0.8;">
                    Cliquez n'importe où pour fermer
                </div>
            </div>
        `;
        
        fullscreenDiv.onclick = function(e) {
            if (e.target === fullscreenDiv) {
                closeFullscreenImage();
            }
        };
        
        document.body.appendChild(fullscreenDiv);
        document.body.style.overflow = 'hidden';
    }
    
    function closeFullscreenImage() {
        const fullscreenImg = document.getElementById('fullscreenImage');
        if (fullscreenImg) {
            fullscreenImg.style.opacity = '0';
            setTimeout(() => {
                fullscreenImg.remove();
                document.body.style.overflow = 'auto';
            }, 200);
        }
    }
</script>
@endpush
