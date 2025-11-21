@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#FF0000; --secondary:#00AA00; --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    .page-bg { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height:100vh; padding:2rem 1rem; }
    .container-max { max-width:1600px; margin:0 auto; }
    .welcome-header { background:linear-gradient(135deg, var(--primary), var(--secondary)); border-radius:20px; padding:3rem 2rem; margin-bottom:2rem; color:#fff; box-shadow:0 10px 40px rgba(59,130,246,.3); position:relative; overflow:hidden; }
    .welcome-header::before { content:''; position:absolute; top:-50%; right:-10%; width:400px; height:400px; background:radial-gradient(circle, rgba(255,255,255,.1) 0%, transparent 70%); border-radius:50%; }
    .welcome-content { position:relative; z-index:2; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:2rem; }
    .welcome-text h1 { font-size:2.5rem; font-weight:800; margin-bottom:.5rem; }
    .welcome-text p { font-size:1.1rem; opacity:.95; }
    .welcome-actions { display:flex; gap:1rem; }
    .btn { padding:.875rem 1.75rem; border-radius:12px; font-weight:600; cursor:pointer; transition:all .3s ease; border:none; display:inline-flex; align-items:center; gap:.5rem; text-decoration:none; font-size:.95rem; }
    .btn-white { background:#fff; color:var(--primary); box-shadow:0 4px 15px rgba(0,0,0,.1); }
    .btn-white:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.15); }
    .btn-outline-white { background:transparent; color:#fff; border:2px solid rgba(255,255,255,.5); }
    .btn-outline-white:hover { background:#fff; color:var(--primary); border-color:#fff; }
    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(280px,1fr)); gap:1.5rem; margin-bottom:2rem; }
    .stat-card { background:#fff; border-radius:20px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,.05); transition:all .3s ease; position:relative; overflow:hidden; }
    .stat-card::before { content:''; position:absolute; top:0; left:0; width:100%; height:4px; background:linear-gradient(90deg, var(--primary), var(--secondary)); }
    .stat-card:hover { transform:translateY(-8px); box-shadow:0 12px 40px rgba(0,0,0,.1); }
    .stat-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; }
    .stat-icon { width:60px; height:60px; border-radius:15px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; box-shadow:0 4px 15px rgba(0,0,0,.1); }
    .stat-icon.blue { background:linear-gradient(135deg, #3b82f6, #2563eb); color:#fff; }
    .stat-icon.orange { background:linear-gradient(135deg, #f97316, #ea580c); color:#fff; }
    .stat-icon.green { background:linear-gradient(135deg, #10b981, #059669); color:#fff; }
    .stat-icon.purple { background:linear-gradient(135deg, #8b5cf6, #7c3aed); color:#fff; }
    .stat-info { text-align:right; }
    .stat-label { font-size:.95rem; color:var(--gray); font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
    .stat-value { font-size:3rem; font-weight:800; color:var(--dark); line-height:1.2; margin-top:.25rem; }
    .stat-footer { display:flex; align-items:center; gap:.5rem; font-size:.9rem; }
    .stat-change { display:flex; align-items:center; gap:.25rem; padding:.25rem .75rem; border-radius:20px; font-weight:600; }
    .stat-change.up { background:rgba(16,185,129,.1); color:var(--success); }
    .stat-change.down { background:rgba(239,68,68,.1); color:var(--danger); }
    .stat-change.neutral { background:rgba(100,116,139,.1); color:var(--gray); }
    .content-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(500px,1fr)); gap:2rem; margin-bottom:2rem; }
    .card { background:#fff; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,.05); overflow:hidden; }
    .card-header { padding:1.5rem 2rem; border-bottom:2px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; }
    .card-header h3 { font-size:1.3rem; font-weight:700; color:var(--dark); display:flex; align-items:center; gap:.75rem; }
    .card-link { color:var(--primary); font-size:.9rem; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:.25rem; transition:all .3s ease; }
    .card-link:hover { gap:.5rem; }
    .card-body { padding:1.5rem 2rem; }
    .activity-list { display:flex; flex-direction:column; gap:1rem; }
    .activity-item { display:flex; gap:1rem; padding:1rem; border-radius:12px; background:#f8fafc; transition:all .3s ease; cursor:pointer; }
    .activity-item:hover { background:#f1f5f9; transform:translateX(5px); }
    .activity-icon { width:45px; height:45px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
    .activity-icon.blue { background:rgba(59,130,246,.1); color:var(--primary); }
    .activity-icon.green { background:rgba(16,185,129,.1); color:var(--success); }
    .activity-icon.orange { background:rgba(249,115,22,.1); color:var(--secondary); }
    .activity-content { flex:1; }
    .activity-title { font-weight:600; color:var(--dark); margin-bottom:.25rem; }
    .activity-meta { font-size:.85rem; color:var(--gray); }
    .activity-badge { padding:.25rem .75rem; border-radius:20px; font-size:.8rem; font-weight:600; background:rgba(59,130,246,.1); color:var(--primary); }
    .quick-actions { display:grid; grid-template-columns:repeat(auto-fit, minmax(240px,1fr)); gap:1.5rem; }
    .action-card { background:#fff; border-radius:16px; padding:2rem; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,.05); transition:all .3s ease; text-decoration:none; border:2px solid transparent; }
    .action-card:hover { transform:translateY(-5px); box-shadow:0 8px 30px rgba(0,0,0,.1); border-color:var(--primary); }
    .action-icon { width:70px; height:70px; margin:0 auto 1rem; border-radius:15px; display:flex; align-items:center; justify-content:center; font-size:2rem; background:linear-gradient(135deg, var(--primary), var(--secondary)); color:#fff; box-shadow:0 8px 20px rgba(59,130,246,.3); }
    .action-title { font-size:1.1rem; font-weight:700; color:var(--dark); margin-bottom:.5rem; }
    .action-desc { font-size:.9rem; color:var(--gray); }
    .badge-admin { background:rgba(239,68,68,.1); color:var(--danger); }
    .badge-gestionnaire { background:rgba(59,130,246,.1); color:var(--primary); }
    .badge-visiteur { background:rgba(100,116,139,.1); color:var(--gray); }
    .empty-state { text-align:center; padding:3rem 1rem; color:var(--gray); }
    .empty-state i { font-size:3rem; margin-bottom:1rem; opacity:.5; }
    @media (max-width:1200px){ .content-grid{ grid-template-columns:1fr; } }
    @media (max-width:768px){ .page-bg{ padding:1rem .5rem; } .welcome-header{ padding:2rem 1.5rem; } .welcome-text h1{ font-size:1.75rem; } .welcome-actions{ flex-direction:column; width:100%; } .btn{ width:100%; justify-content:center; } .stats-grid{ grid-template-columns:1fr; } .stat-value{ font-size:2.5rem; } .quick-actions{ grid-template-columns:repeat(2,1fr); } }
    @media (max-width:480px){ .quick-actions{ grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="page-bg">
    <div class="container-max">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <div class="welcome-content">
                <div class="welcome-text">
                    <h1>👋 Bienvenue, Administrateur</h1>
                    <p>Tableau de bord de gestion du patrimoine culturel du Burkina Faso</p>
                </div>
                <div class="welcome-actions">
                    <a href="{{ route('demande.create') }}" class="btn btn-white">
                        <i class="fas fa-plus"></i>
                        Formulaire de demande
                    </a>
                    <a href="{{ route('gestionnaire.demandes') }}" class="btn btn-outline-white">
                        <i class="fas fa-cog"></i>
                        Demandes
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Utilisateurs</div>
                        <div class="stat-value">{{ $stats['total_utilisateurs'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="stat-change up">
                        <i class="fas fa-arrow-up"></i>
                        +12%
                    </span>
                    <span style="color: var(--gray);">vs mois dernier</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon orange">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Demandes</div>
                        <div class="stat-value">{{ $stats['total_demandes'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="stat-change up">
                        <i class="fas fa-arrow-up"></i>
                        +8%
                    </span>
                    <span style="color: var(--gray);">vs mois dernier</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon green">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Patrimoines</div>
                        <div class="stat-value">{{ $stats['total_patrimoines'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="stat-change neutral">
                        <i class="fas fa-minus"></i>
                        Stable
                    </span>
                    <span style="color: var(--gray);">vs mois dernier</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon purple">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Validations</div>
                        <div class="stat-value">{{ $stats['total_validations'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="stat-footer">
                    <span class="stat-change up">
                        <i class="fas fa-arrow-up"></i>
                        +15%
                    </span>
                    <span style="color: var(--gray);">vs mois dernier</span>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Recent Requests -->
            <div class="card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-clock"></i>
                        Demandes Récentes
                    </h3>
                    <a href="#" class="card-link">
                        Voir tout
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @forelse(($demandesRecentes ?? []) as $d)
                        <div class="activity-item">
                            <div class="activity-icon blue">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Demande #{{ $d->id_demande }} - {{ optional($d->demandeur)->nom }} {{ optional($d->demandeur)->prenom }}</div>
                                <div class="activity-meta">
                                    <i class="fas fa-calendar"></i> {{ $d->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <span class="activity-badge">Nouveau</span>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Aucune demande récente</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-user-plus"></i>
                        Utilisateurs Récents
                    </h3>
                    <a href="{{ route('admin.utilisateurs') }}" class="card-link">
                        Voir tout
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="activity-list">
                        @forelse(($utilisateursRecents ?? []) as $u)
                        <div class="activity-item">
                            <div class="activity-icon {{ $u->type_utilisateur === 'admin' ? 'orange' : ($u->type_utilisateur === 'gestionnaire' ? 'blue' : 'green') }}">
                                <i class="fas fa-user{{ $u->type_utilisateur === 'admin' ? '-shield' : '' }}"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $u->name }}</div>
                                <div class="activity-meta">
                                    {{ $u->email }}
                                </div>
                            </div>
                            <span class="activity-badge {{ $u->type_utilisateur === 'admin' ? 'badge-admin' : ($u->type_utilisateur === 'gestionnaire' ? 'badge-gestionnaire' : 'badge-visiteur') }}">
                                {{ ucfirst($u->type_utilisateur) }}
                            </span>
                        </div>
                        @empty
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Aucun utilisateur récent</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="card" style="margin-bottom: 2rem;">
            <div class="card-header">
                <h3>
                    <i class="fas fa-bolt"></i>
                    Navigation Rapide
                </h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="{{ route('admin.utilisateurs') }}" class="action-card">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="action-title">Utilisateurs</div>
                        <div class="action-desc">Gérer les comptes utilisateurs</div>
                    </a>

                    <a href="{{ route('admin.roles') }}" class="action-card">
                        <div class="action-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="action-title">Rôles</div>
                        <div class="action-desc">Configuration des permissions</div>
                    </a>

                    <a href="{{ route('admin.categories') }}" class="action-card">
                        <div class="action-icon" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="action-title">Catégories</div>
                        <div class="action-desc">Gérer les catégories</div>
                    </a>

                    <a href="{{ route('admin.patrimoines') }}" class="action-card">
                        <div class="action-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <div class="action-title">Patrimoines</div>
                        <div class="action-desc">Base de données patrimoine</div>
                    </a>

                    <a href="" class="action-card">
                        <div class="action-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="action-title">Demandes</div>
                        <div class="action-desc">Validation des demandes</div>
                    </a>

                    <a href="{{ route('admin.statistiques') }}" class="action-card">
                        <div class="action-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="action-title">Statistiques</div>
                        <div class="action-desc">Rapports et analyses</div>
                    </a>

                    <a href="" class="action-card">
                        <div class="action-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="action-title">Paramètres</div>
                        <div class="action-desc">Configuration système</div>
                    </a>

                    <a href="" class="action-card">
                        <div class="action-icon" style="background: linear-gradient(135deg, #64748b, #475569);">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="action-title">Exports</div>
                        <div class="action-desc">Exporter les données</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.stat-card, .card, .action-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';

            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });

    // Hover effects
    document.querySelectorAll('.activity-item').forEach(item => {
        item.addEventListener('click', function() {
            console.log('Item clicked:', this.querySelector('.activity-title').textContent);
        });
    });
</script>
@endsection


