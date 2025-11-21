@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#FF0000; --secondary:#00AA00; --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    
    body { font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; background:linear-gradient(135deg,#f8fafc 0%,#e2e8f0 100%); min-height:100vh; padding:2rem 1rem; }
    .container { max-width:1600px; margin:0 auto; }

    /* Welcome Header */
    .welcome-header { background:linear-gradient(135deg,var(--primary),var(--secondary)); border-radius:20px; padding:2.5rem 2rem; margin-bottom:2rem; color:#fff; box-shadow:0 10px 40px rgba(59,130,246,.3); position:relative; overflow:hidden; }
    .welcome-header::before { content:''; position:absolute; top:-50%; right:-10%; width:400px; height:400px; background:radial-gradient(circle,rgba(255,255,255,.1) 0%,transparent 70%); border-radius:50%; }
    .welcome-content { position:relative; z-index:2; }
    .welcome-content h1 { font-size:2.2rem; font-weight:800; margin-bottom:.5rem; }
    .welcome-content p { font-size:1.1rem; opacity:.95; }

    /* Stats Grid */
    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1.5rem; margin-bottom:2rem; }
    .stat-card { background:#fff; border-radius:20px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,.05); transition:all .3s ease; position:relative; overflow:hidden; }
    .stat-card::before { content:''; position:absolute; top:0; left:0; width:100%; height:4px; }
    .stat-card.primary::before { background:linear-gradient(90deg,var(--primary),#2563eb); }
    .stat-card.warning::before { background:linear-gradient(90deg,var(--warning),#d97706); }
    .stat-card.success::before { background:linear-gradient(90deg,var(--success),#059669); }
    .stat-card.danger::before { background:linear-gradient(90deg,var(--danger),#dc2626); }
    .stat-card:hover { transform:translateY(-8px); box-shadow:0 12px 40px rgba(0,0,0,.1); }
    
    .stat-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; }
    .stat-info { flex:1; }
    .stat-label { font-size:.85rem; color:var(--gray); font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin-bottom:.5rem; }
    .stat-value { font-size:3rem; font-weight:800; color:var(--dark); line-height:1; }
    .stat-icon { width:60px; height:60px; border-radius:15px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; flex-shrink:0; }
    .stat-icon.blue { background:rgba(59,130,246,.1); color:var(--primary); }
    .stat-icon.orange { background:rgba(249,115,22,.1); color:var(--secondary); }
    .stat-icon.green { background:rgba(16,185,129,.1); color:var(--success); }
    .stat-icon.red { background:rgba(239,68,68,.1); color:var(--danger); }
    .stat-footer { font-size:.9rem; color:var(--gray); display:flex; align-items:center; gap:.5rem; }
    .stat-trend { display:inline-flex; align-items:center; gap:.25rem; padding:.25rem .75rem; border-radius:20px; font-weight:600; font-size:.8rem; }
    .stat-trend.up { background:rgba(16,185,129,.1); color:var(--success); }
    .stat-trend.down { background:rgba(239,68,68,.1); color:var(--danger); }

    /* Quick Actions */
    .actions-section { background:#fff; border-radius:20px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,.05); margin-bottom:2rem; }
    .section-header { display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem; }
    .section-icon { width:50px; height:50px; background:linear-gradient(135deg,var(--primary),var(--secondary)); border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.5rem; }
    .section-title { font-size:1.4rem; font-weight:700; color:var(--dark); }
    
    .actions-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:1.5rem; }
    .action-card { background:#fff; border:2px solid #e2e8f0; border-radius:16px; padding:2rem; text-align:center; transition:all .3s ease; text-decoration:none; display:block; }
    .action-card:hover { transform:translateY(-5px); box-shadow:0 12px 40px rgba(0,0,0,.1); border-color:var(--primary); }
    .action-icon { width:70px; height:70px; margin:0 auto 1rem; border-radius:15px; display:flex; align-items:center; justify-content:center; font-size:2rem; }
    .action-icon.blue { background:linear-gradient(135deg,var(--primary),#2563eb); color:#fff; }
    .action-icon.green { background:linear-gradient(135deg,var(--success),#059669); color:#fff; }
    .action-icon.cyan { background:linear-gradient(135deg,#06b6d4,#0891b2); color:#fff; }
    .action-icon.orange { background:linear-gradient(135deg,var(--warning),#d97706); color:#fff; }
    .action-title { font-size:1.1rem; font-weight:700; color:var(--dark); margin-bottom:.5rem; }
    .action-desc { font-size:.9rem; color:var(--gray); }

    /* Recent Activity */
    .recent-section { background:#fff; border-radius:20px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,.05); }
    .activity-list { display:flex; flex-direction:column; gap:1rem; }
    .activity-item { display:flex; gap:1rem; padding:1rem; border-radius:12px; background:#f8fafc; transition:all .3s ease; }
    .activity-item:hover { background:#f1f5f9; transform:translateX(5px); }
    .activity-icon { width:45px; height:45px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
    .activity-icon.blue { background:rgba(59,130,246,.1); color:var(--primary); }
    .activity-icon.green { background:rgba(16,185,129,.1); color:var(--success); }
    .activity-icon.orange { background:rgba(249,115,22,.1); color:var(--secondary); }
    .activity-content { flex:1; }
    .activity-title { font-weight:600; color:var(--dark); margin-bottom:.25rem; }
    .activity-meta { font-size:.85rem; color:var(--gray); }
    .activity-badge { padding:.25rem .75rem; border-radius:20px; font-size:.8rem; font-weight:600; }
    .badge-new { background:rgba(59,130,246,.1); color:var(--primary); }
    .badge-pending { background:rgba(245,158,11,.1); color:var(--warning); }

    /* Responsive */
    @media (max-width:768px) {
        body { padding:1rem .5rem; }
        .welcome-header { padding:2rem 1.5rem; }
        .welcome-content h1 { font-size:1.75rem; }
        .stats-grid { grid-template-columns:1fr; }
        .stat-value { font-size:2.5rem; }
        .actions-grid { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('title', 'Dashboard Gestionnaire')
@section('content')
<div class="container">
    <!-- Welcome Header -->
    <div class="welcome-header">
        <div class="welcome-content">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard Gestionnaire</h1>
            <p>Vue d'ensemble de vos activités de gestion du patrimoine</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Demandes en attente -->
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-info">
                    <div class="stat-label">Demandes en attente</div>
                    <div class="stat-value">{{ $stats['demandes_attente'] }}</div>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> +8
                </span>
                <span>cette semaine</span>
            </div>
        </div>

        <!-- En cours de traitement -->
        <div class="stat-card warning">
            <div class="stat-header">
                <div class="stat-info">
                    <div class="stat-label">En cours de traitement</div>
                    <div class="stat-value">{{ $stats['demandes_cours'] }}</div>
                </div>
                <div class="stat-icon orange">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-trend down">
                    <i class="fas fa-arrow-down"></i> -3
                </span>
                <span>vs semaine dernière</span>
            </div>
        </div>

        <!-- Demandes validées -->
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-info">
                    <div class="stat-label">Demandes validées</div>
                    <div class="stat-value">{{ $stats['demandes_validees'] }}</div>
                </div>
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> +18
                </span>
                <span>ce mois-ci</span>
            </div>
        </div>

        <!-- Demandes rejetées -->
        <div class="stat-card danger">
            <div class="stat-header">
                <div class="stat-info">
                    <div class="stat-label">Demandes rejetées</div>
                    <div class="stat-value">{{ $stats['demandes_rejetees'] }}</div>
                </div>
                <div class="stat-icon red">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="stat-footer">
                <span class="stat-trend up" style="background:rgba(100,116,139,.1); color:var(--gray);">
                    <i class="fas fa-minus"></i> Stable
                </span>
                <span>ce mois-ci</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="actions-section">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <h2 class="section-title">Actions Rapides</h2>
        </div>
        <div class="actions-grid">
            <a href="{{ route('gestionnaire.demandes') }}" class="action-card">
                <div class="action-icon blue">
                    <i class="fas fa-list"></i>
                </div>
                <div class="action-title">Toutes les demandes</div>
                <div class="action-desc">Consulter et gérer les demandes</div>
            </a>

            <a href="{{ route('gestionnaire.detenteurs') }}" class="action-card">
                <div class="action-icon green">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="action-title">Gérer les détenteurs</div>
                <div class="action-desc">Liste des détenteurs vérifiés</div>
            </a>

            <a href="{{ route('demande.create') }}" class="action-card">
                <div class="action-icon cyan">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="action-title">Formulaire public</div>
                <div class="action-desc">Voir le formulaire d'inscription</div>
            </a>

            <a href="{{ route('patrimoines.index') }}" class="action-card">
                <div class="action-icon orange">
                    <i class="fas fa-landmark"></i>
                </div>
                <div class="action-title">Éléments patrimoniaux</div>
                <div class="action-desc">Base de données du patrimoine</div>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="recent-section">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-history"></i>
            </div>
            <h2 class="section-title">Activités Récentes</h2>
        </div>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon blue">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Nouvelle demande reçue</div>
                    <div class="activity-meta">
                        Amadou Konaté - Tradition orale - Il y a 15 minutes
                    </div>
                </div>
                <span class="activity-badge badge-new">Nouveau</span>
            </div>

            <div class="activity-item">
                <div class="activity-icon green">
                    <i class="fas fa-check"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Demande validée</div>
                    <div class="activity-meta">
                        Fatima Ouédraogo - Tissage Faso Dan Fani - Il y a 2 heures
                    </div>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon blue">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Nouvelle demande reçue</div>
                    <div class="activity-meta">
                        Ibrahim Sawadogo - Danse des masques - Il y a 4 heures
                    </div>
                </div>
                <span class="activity-badge badge-new">Nouveau</span>
            </div>

            <div class="activity-item">
                <div class="activity-icon orange">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Demande en attente de révision</div>
                    <div class="activity-meta">
                        Marie Kaboré - Médecine traditionnelle - Il y a 1 jour
                    </div>
                </div>
                <span class="activity-badge badge-pending">En attente</span>
            </div>

            <div class="activity-item">
                <div class="activity-icon green">
                    <i class="fas fa-check"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Demande validée</div>
                    <div class="activity-meta">
                        Boubacar Traoré - Forge traditionnelle - Il y a 2 jours
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.stat-card, .actions-section, .recent-section');
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
