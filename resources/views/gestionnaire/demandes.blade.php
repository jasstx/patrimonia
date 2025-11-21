@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#3b82f6; --secondary:#f97316; --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    
    body { font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; background:linear-gradient(135deg,#f8fafc 0%,#e2e8f0 100%); min-height:100vh; padding:2rem 1rem; }
    .container { max-width:1600px; margin:0 auto; }

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

    /* Stats Cards */
    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.5rem; margin-bottom:2rem; }
    .stat-card { background:#fff; border-radius:16px; padding:1.5rem; box-shadow:0 4px 15px rgba(0,0,0,.05); transition:all .3s ease; border-left:4px solid var(--primary); }
    .stat-card:hover { transform:translateY(-5px); box-shadow:0 8px 30px rgba(0,0,0,.1); }
    .stat-title { font-size:.85rem; color:var(--gray); font-weight:600; text-transform:uppercase; margin-bottom:.5rem; }
    .stat-value { font-size:2rem; font-weight:800; color:var(--dark); }
    .stat-card.warning { border-left-color:var(--warning); }
    .stat-card.danger { border-left-color:var(--danger); }

    /* Filter Section */
    .filter-section { background:#fff; border-radius:16px; padding:1.5rem; margin-bottom:2rem; box-shadow:0 4px 15px rgba(0,0,0,.05); }
    .filter-grid { display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:1rem; align-items:end; }
    .form-group { display:flex; flex-direction:column; gap:.5rem; }
    .form-group label { font-weight:600; color:var(--dark); font-size:.9rem; }
    .form-control { padding:.75rem 1rem; border:2px solid #e2e8f0; border-radius:10px; font-size:.95rem; transition:all .3s ease; }
    .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    .btn-primary { background:linear-gradient(135deg,var(--primary),var(--secondary)); color:#fff; box-shadow:0 4px 15px rgba(59,130,246,.3); }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(59,130,246,.4); }

    /* Main Card */
    .main-card { background:#fff; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,.05); overflow:hidden; }
    .card-header { background:linear-gradient(135deg,var(--primary),var(--secondary)); color:#fff; padding:1.5rem 2rem; display:flex; justify-content:space-between; align-items:center; }
    .card-header h2 { font-size:1.3rem; font-weight:700; display:flex; align-items:center; gap:.75rem; margin:0; }

    /* Table */
    table { width:100%; border-collapse:collapse; }
    thead { background:linear-gradient(135deg,#f8fafc,#e2e8f0); }
    thead th { padding:1.25rem 1rem; text-align:left; font-weight:700; color:var(--dark); font-size:.875rem; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; }
    tbody tr { border-bottom:1px solid #f1f5f9; transition:all .2s ease; }
    tbody tr:hover { background:#f8fafc; }
    tbody td { padding:1.25rem 1rem; color:var(--gray); vertical-align:middle; }

    /* Demandeur Info */
    .demandeur-info { display:flex; align-items:center; gap:1rem; }
    .demandeur-avatar { width:45px; height:45px; border-radius:50%; background:linear-gradient(135deg,var(--primary),var(--secondary)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1.1rem; flex-shrink:0; }
    .demandeur-details { display:flex; flex-direction:column; gap:.25rem; }
    .demandeur-name { font-weight:700; color:var(--dark); font-size:.95rem; }
    .demandeur-phone { font-size:.85rem; color:var(--gray); }

    /* Badge */
    .badge { display:inline-flex; align-items:center; gap:.5rem; padding:.35rem .9rem; border-radius:20px; font-size:.8rem; font-weight:600; }
    .badge-pending { background:rgba(245,158,11,.1); color:var(--warning); }
    .badge-progress { background:rgba(59,130,246,.1); color:var(--primary); }
    .badge-validated { background:rgba(16,185,129,.1); color:var(--success); }
    .badge-rejected { background:rgba(239,68,68,.1); color:var(--danger); }

    /* Action Button */
    .btn-view { padding:.5rem 1rem; background:rgba(59,130,246,.1); color:var(--primary); border-radius:8px; text-decoration:none; font-weight:600; font-size:.875rem; transition:all .3s ease; display:inline-flex; align-items:center; gap:.5rem; }
    .btn-view:hover { background:var(--primary); color:#fff; }

    /* Empty State */
    .empty-state { text-align:center; padding:4rem 2rem; color:var(--gray); }
    .empty-state i { font-size:4rem; margin-bottom:1rem; opacity:.3; }
    .empty-state h3 { font-size:1.5rem; color:var(--dark); margin-bottom:.5rem; }

    /* Table Footer */
    .table-footer { padding:1.5rem 2rem; background:#f8fafc; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .pagination { display:flex; gap:.5rem; }
    .page-link { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; border:2px solid #e2e8f0; background:#fff; color:var(--gray); cursor:pointer; transition:all .3s ease; text-decoration:none; }
    .page-link:hover, .page-link.active { background:var(--primary); color:#fff; border-color:var(--primary); }

    /* Responsive */
    @media (max-width:1024px) { .filter-grid { grid-template-columns:1fr 1fr; } .stats-grid { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:768px) { body { padding:1rem .5rem; } .page-header { flex-direction:column; align-items:flex-start; } .filter-grid { grid-template-columns:1fr; } .stats-grid { grid-template-columns:1fr; } .demandeur-avatar { width:35px; height:35px; font-size:.9rem; } }
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
                <h1>Demandes à traiter</h1>
                <p>Gestion et validation des demandes d'inscription</p>
            </div>
        </div>
        <a href="{{ route('gestionnaire.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Demandes</div>
            <div class="stat-value">{{ $demandes->total() }}</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-title">En Attente</div>
            <div class="stat-value">{{ $demandes->where('status', 'en_attente')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">En Traitement</div>
            <div class="stat-value">{{ $demandes->where('status', 'en_cours')->count() }}</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-title">Urgent</div>
            <div class="stat-value">{{ $demandes->where('status', 'urgent')->count() }}</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form class="filter-grid">
            <div class="form-group">
                <label><i class="fas fa-search"></i> Rechercher</label>
                <input type="text" class="form-control" placeholder="Nom, téléphone..." id="searchInput">
            </div>
            <div class="form-group">
                <label><i class="fas fa-filter"></i> Statut</label>
                <select class="form-control">
                    <option value="">Tous</option>
                    <option value="en_attente">En attente</option>
                    <option value="en_cours">En cours</option>
                    <option value="valide">Validé</option>
                    <option value="rejete">Rejeté</option>
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar"></i> Date</label>
                <input type="date" class="form-control">
            </div>
            <div style="display:flex; align-items:flex-end;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Main Table -->
    <div class="main-card">
        <div class="card-header">
            <h2>
                <i class="fas fa-list"></i>
                Liste des Demandes
            </h2>
            <div style="background:rgba(255,255,255,.2); padding:.5rem 1rem; border-radius:20px; font-size:.9rem;">
                <i class="fas fa-hashtag"></i> {{ $demandes->total() }} demandes
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table id="demandesTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Demandeur</th>
                        <th><i class="fas fa-landmark"></i> Type</th>
                        <th><i class="fas fa-map-marker-alt"></i> Localisation</th>
                        <th><i class="fas fa-info-circle"></i> Statut</th>
                        <th><i class="fas fa-calendar"></i> Date</th>
                        <th style="text-align:center;"><i class="fas fa-cog"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($demandes as $d)
                    <tr>
                        <td><strong style="color:var(--primary);">#{{ $d->id_demande }}</strong></td>
                        <td>
                            <div class="demandeur-info">
                                <div class="demandeur-avatar">
                                    {{ strtoupper(substr(optional($d->demandeur)->nom ?? 'A', 0, 1) . substr(optional($d->demandeur)->prenom ?? 'A', 0, 1)) }}
                                </div>
                                <div class="demandeur-details">
                                    <span class="demandeur-name">{{ optional($d->demandeur)->nom }} {{ optional($d->demandeur)->prenom }}</span>
                                    <span class="demandeur-phone">
                                        <i class="fas fa-phone"></i> {{ optional($d->demandeur)->telephone }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-weight:600; color:var(--dark);">
                                {{ optional($d->demandeur)->type_detenteur_formate ?? 'Individu' }}
                            </span>
                            @if(in_array(optional($d->demandeur)->type_detenteur, ['famille', 'communaute']) && optional($d->demandeur)->nom_structure)
                            <br><small style="color:var(--gray); font-size:.8rem;">
                                {{ optional($d->demandeur)->nom_structure }}
                            </small>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex; flex-direction:column; gap:.25rem;">
                                @if(optional($d->demandeur)->province)
                                <span style="font-weight:600; color:var(--dark); font-size:.9rem;">
                                    <i class="fas fa-map-marker-alt" style="color:var(--primary);"></i>
                                    {{ optional($d->demandeur)->province }}
                                </span>
                                @endif
                                @if(optional($d->demandeur)->commune)
                                <span style="color:var(--gray); font-size:.85rem;">
                                    {{ optional($d->demandeur)->commune }}
                                </span>
                                @endif
                                @if(!optional($d->demandeur)->province && !optional($d->demandeur)->commune)
                                <span style="color:var(--gray); font-style:italic; font-size:.85rem;">
                                    Non renseigné
                                </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = match($d->status) {
                                    'en_attente' => 'badge-pending',
                                    'en_cours' => 'badge-progress',
                                    'valide' => 'badge-validated',
                                    'rejete' => 'badge-rejected',
                                    default => 'badge-pending'
                                };
                                $statusIcon = match($d->status) {
                                    'en_attente' => 'fas fa-clock',
                                    'en_cours' => 'fas fa-spinner',
                                    'valide' => 'fas fa-check',
                                    'rejete' => 'fas fa-times',
                                    default => 'fas fa-clock'
                                };
                                $statusText = match($d->status) {
                                    'en_attente' => 'En attente',
                                    'en_cours' => 'En cours',
                                    'valide' => 'Validé',
                                    'rejete' => 'Rejeté',
                                    default => ucfirst($d->status)
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                <i class="{{ $statusIcon }}"></i> {{ $statusText }}
                            </span>
                        </td>
                        <td>{{ optional($d->created_at)->format('d/m/Y H:i') }}</td>
                        <td style="text-align:center;">
                            <a href="{{ route('gestionnaire.demandes.show', $d->id_demande) }}" class="btn-view">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>Aucune demande trouvée</h3>
                            <p>Aucune demande en attente de traitement.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div style="color:var(--gray);">
                <i class="fas fa-info-circle"></i>
                <strong>{{ $demandes->count() }}</strong> demande(s) affichée(s)
            </div>
            <div class="pagination">
                {{ $demandes->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#demandesTable tbody tr');

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.stat-card, .filter-section, .main-card');
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









