@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#FF0000; --secondary:#00AA00; --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    
    body { font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; background:linear-gradient(135deg,#f8fafc 0%,#e2e8f0 100%); min-height:100vh; padding:2rem 1rem; }
    .container { max-width:1600px; margin:0 auto; }

    /* Alert */
    .alert { padding:1rem 1.5rem; border-radius:12px; margin-bottom:1.5rem; display:flex; align-items:center; gap:1rem; animation:slideDown .3s ease; }
    @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
    .alert-success { background:rgba(16,185,129,.1); border:2px solid var(--success); color:var(--success); }

    /* Page Header */
    .page-header { background:#fff; border-radius:20px; padding:2rem; margin-bottom:2rem; box-shadow:0 4px 20px rgba(0,0,0,.05); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .header-left { display:flex; align-items:center; gap:1rem; }
    .header-icon { width:60px; height:60px; background:linear-gradient(135deg,var(--success),#059669); border-radius:15px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; color:#fff; }
    .page-header h1 { font-size:2rem; font-weight:800; color:var(--dark); margin-bottom:.25rem; }
    .page-header p { color:var(--gray); font-size:.95rem; }
    .btn { padding:.75rem 1.5rem; border-radius:10px; font-weight:600; cursor:pointer; transition:all .3s ease; border:none; display:inline-flex; align-items:center; gap:.5rem; text-decoration:none; font-size:.95rem; }
    .btn-secondary { background:#fff; color:var(--gray); border:2px solid #e2e8f0; }
    .btn-secondary:hover { background:var(--light); border-color:var(--gray); }

    /* Stats Cards */
    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1.5rem; margin-bottom:2rem; }
    .stat-card { background:#fff; border-radius:16px; padding:1.5rem; box-shadow:0 4px 15px rgba(0,0,0,.05); transition:all .3s ease; border-left:4px solid var(--success); }
    .stat-card:hover { transform:translateY(-5px); box-shadow:0 8px 30px rgba(0,0,0,.1); }
    .stat-title { font-size:.85rem; color:var(--gray); font-weight:600; text-transform:uppercase; margin-bottom:.5rem; }
    .stat-value { font-size:2rem; font-weight:800; color:var(--dark); }

    /* Filter Section */
    .filter-section { background:#fff; border-radius:16px; padding:1.5rem; margin-bottom:2rem; box-shadow:0 4px 15px rgba(0,0,0,.05); }
    .filter-grid { display:grid; grid-template-columns:2fr 1fr auto; gap:1rem; align-items:end; }
    .form-group { display:flex; flex-direction:column; gap:.5rem; }
    .form-group label { font-weight:600; color:var(--dark); font-size:.9rem; }
    .form-control { padding:.75rem 1rem; border:2px solid #e2e8f0; border-radius:10px; font-size:.95rem; transition:all .3s ease; }
    .form-control:focus { outline:none; border-color:var(--success); box-shadow:0 0 0 3px rgba(16,185,129,.1); }
    .btn-primary { background:linear-gradient(135deg,var(--success),#059669); color:#fff; box-shadow:0 4px 15px rgba(16,185,129,.3); }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(16,185,129,.4); }

    /* Main Card */
    .main-card { background:#fff; border-radius:20px; box-shadow:0 4px 20px rgba(0,0,0,.05); overflow:hidden; }
    .card-header { background:linear-gradient(135deg,var(--success),#059669); color:#fff; padding:1.5rem 2rem; display:flex; justify-content:space-between; align-items:center; }
    .card-header h2 { font-size:1.3rem; font-weight:700; display:flex; align-items:center; gap:.75rem; margin:0; }

    /* Table */
    table { width:100%; border-collapse:collapse; }
    thead { background:linear-gradient(135deg,#f8fafc,#e2e8f0); }
    thead th { padding:1.25rem 1rem; text-align:left; font-weight:700; color:var(--dark); font-size:.875rem; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; }
    tbody tr { border-bottom:1px solid #f1f5f9; transition:all .2s ease; }
    tbody tr:hover { background:#f8fafc; }
    tbody td { padding:1.25rem 1rem; color:var(--gray); vertical-align:middle; }

    /* Detenteur Info */
    .detenteur-info { display:flex; align-items:center; gap:1rem; }
    .detenteur-avatar { width:50px; height:50px; border-radius:50%; background:linear-gradient(135deg,var(--success),#059669); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1.1rem; flex-shrink:0; overflow:hidden; }
    .detenteur-avatar img { width:100%; height:100%; object-fit:cover; }
    .detenteur-details { display:flex; flex-direction:column; gap:.25rem; }
    .detenteur-name { font-weight:700; color:var(--dark); font-size:.95rem; }
    .detenteur-phone { font-size:.85rem; color:var(--gray); }

    /* Badge */
    .badge { display:inline-flex; align-items:center; gap:.5rem; padding:.35rem .9rem; border-radius:20px; font-size:.8rem; font-weight:600; }
    .badge-individuel { background:rgba(59,130,246,.1); color:var(--primary); }
    .badge-communautaire { background:rgba(249,115,22,.1); color:var(--secondary); }
    .badge-verified { background:rgba(16,185,129,.1); color:var(--success); }

    /* Action Button */
    .btn-view { padding:.5rem 1rem; background:rgba(16,185,129,.1); color:var(--success); border-radius:8px; text-decoration:none; font-weight:600; font-size:.875rem; transition:all .3s ease; display:inline-flex; align-items:center; gap:.5rem; }
    .btn-view:hover { background:var(--success); color:#fff; }

    /* Empty State */
    .empty-state { text-align:center; padding:4rem 2rem; color:var(--gray); }
    .empty-state i { font-size:4rem; margin-bottom:1rem; opacity:.3; }
    .empty-state h3 { font-size:1.5rem; color:var(--dark); margin-bottom:.5rem; }

    /* Table Footer */
    .table-footer { padding:1.5rem 2rem; background:#f8fafc; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .pagination { display:flex; gap:.5rem; }
    .page-link { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; border:2px solid #e2e8f0; background:#fff; color:var(--gray); cursor:pointer; transition:all .3s ease; text-decoration:none; }
    .page-link:hover, .page-link.active { background:var(--success); color:#fff; border-color:var(--success); }

    /* Responsive */
    @media (max-width:1024px) { .filter-grid { grid-template-columns:1fr 1fr; } .stats-grid { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:768px) { body { padding:1rem .5rem; } .page-header { flex-direction:column; align-items:flex-start; } .filter-grid { grid-template-columns:1fr; } .stats-grid { grid-template-columns:1fr; } .detenteur-avatar { width:40px; height:40px; font-size:.9rem; } }
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
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h1>Détenteurs validés</h1>
                <p>Liste des détenteurs acceptés et enregistrés dans le répertoire</p>
            </div>
        </div>
        <a href="{{ route('gestionnaire.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Détenteurs</div>
            <div class="stat-value">{{ $detenteurs->total() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Individuels</div>
            <div class="stat-value">{{ $detenteurs->where('type_detenteur', 'individuel')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Communautaires</div>
            <div class="stat-value">{{ $detenteurs->where('type_detenteur', 'communautaire')->count() }}</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('gestionnaire.detenteurs.valides') }}" class="filter-grid">
            <div class="form-group">
                <label><i class="fas fa-search"></i> Rechercher</label>
                <input type="text" name="q" class="form-control" placeholder="Nom, téléphone, structure..." value="{{ request('q') }}">
            </div>
            <div class="form-group">
                <label><i class="fas fa-filter"></i> Type</label>
                <select name="type" class="form-control">
                    <option value="">Tous les types</option>
                    <option value="individuel" {{ request('type') == 'individuel' ? 'selected' : '' }}>Individuel</option>
                    <option value="communautaire" {{ request('type') == 'communautaire' ? 'selected' : '' }}>Communautaire</option>
                </select>
            </div>
            <div style="display:flex; align-items:flex-end; gap:.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                @if(request()->hasAny(['q', 'type']))
                <a href="{{ route('gestionnaire.detenteurs.valides') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Main Table -->
    <div class="main-card">
        <div class="card-header">
            <h2>
                <i class="fas fa-check-circle"></i>
                Liste des Détenteurs Validés
            </h2>
            <div style="background:rgba(255,255,255,.2); padding:.5rem 1rem; border-radius:20px; font-size:.9rem;">
                <i class="fas fa-hashtag"></i> {{ $detenteurs->total() }} détenteur(s)
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Détenteur</th>
                        <th><i class="fas fa-tag"></i> Type</th>
                        <th><i class="fas fa-landmark"></i> Patrimoines</th>
                        <th><i class="fas fa-map-marker-alt"></i> Localisation</th>
                        <th><i class="fas fa-calendar-check"></i> Date validation</th>
                        <th><i class="fas fa-user-check"></i> Validé par</th>
                        <th style="text-align:center;"><i class="fas fa-cog"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detenteurs as $detenteur)
                    <tr>
                        <td><strong style="color:var(--success);">#{{ $detenteur->id_detenteur }}</strong></td>
                        <td>
                            <div class="detenteur-info">
                                <div class="detenteur-avatar">
                                    @if($detenteur->photo_url && $detenteur->photo_url !== asset('images/default-avatar.png'))
                                        <img src="{{ $detenteur->photo_url }}" alt="Photo">
                                    @else
                                        {{ strtoupper(substr(optional($detenteur->demandeur)->nom ?? 'D', 0, 1) . substr(optional($detenteur->demandeur)->prenom ?? 'T', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="detenteur-details">
                                    <span class="detenteur-name">
                                        {{ optional($detenteur->demandeur)->nom }} {{ optional($detenteur->demandeur)->prenom }}
                                        @if($detenteur->demandeur && $detenteur->demandeur->nom_structure)
                                            <br><small style="color:var(--gray); font-size:.8rem;">{{ $detenteur->demandeur->nom_structure }}</small>
                                        @endif
                                    </span>
                                    @if($detenteur->demandeur && $detenteur->demandeur->telephone)
                                    <span class="detenteur-phone">
                                        <i class="fas fa-phone"></i> {{ $detenteur->demandeur->telephone }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $detenteur->type_detenteur === 'individuel' ? 'badge-individuel' : 'badge-communautaire' }}">
                                <i class="fas fa-{{ $detenteur->type_detenteur === 'individuel' ? 'user' : 'users' }}"></i>
                                {{ ucfirst($detenteur->type_detenteur) }}
                            </span>
                        </td>
                        <td>
                            @if($detenteur->patrimoines->count() > 0)
                                <span style="font-weight:600; color:var(--dark);">{{ $detenteur->patrimoines->count() }} élément(s)</span>
                                <br><small style="color:var(--gray); font-size:.8rem;">
                                    @foreach($detenteur->patrimoines->take(2) as $pat)
                                        {{ $pat->domaine }}-{{ $pat->numero_element }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                    @if($detenteur->patrimoines->count() > 2)
                                        ...
                                    @endif
                                </small>
                            @else
                                <span style="color:var(--gray); font-style:italic;">Aucun</span>
                            @endif
                        </td>
                        <td>
                            @if($detenteur->demandeur)
                                <div style="display:flex; flex-direction:column; gap:.25rem;">
                                    @if($detenteur->demandeur->province)
                                    <span style="font-weight:600; color:var(--dark); font-size:.9rem;">
                                        <i class="fas fa-map-marker-alt" style="color:var(--success);"></i>
                                        {{ $detenteur->demandeur->province }}
                                    </span>
                                    @endif
                                    @if($detenteur->demandeur->commune)
                                    <span style="color:var(--gray); font-size:.85rem;">
                                        {{ $detenteur->demandeur->commune }}
                                    </span>
                                    @endif
                                    @if(!$detenteur->demandeur->province && !$detenteur->demandeur->commune)
                                    <span style="color:var(--gray); font-style:italic; font-size:.85rem;">
                                        Non renseigné
                                    </span>
                                    @endif
                                </div>
                            @else
                                <span style="color:var(--gray); font-style:italic;">Non renseigné</span>
                            @endif
                        </td>
                        <td>
                            @if($detenteur->date_verification)
                                <span style="font-weight:600; color:var(--dark);">
                                    {{ $detenteur->date_verification->format('d/m/Y') }}
                                </span>
                                <br><small style="color:var(--gray); font-size:.8rem;">
                                    {{ $detenteur->date_verification->format('H:i') }}
                                </small>
                            @else
                                <span style="color:var(--gray); font-style:italic;">Non renseigné</span>
                            @endif
                        </td>
                        <td>
                            @if($detenteur->verificateur)
                                <span style="font-weight:600; color:var(--dark);">
                                    {{ $detenteur->verificateur->name }}
                                </span>
                            @else
                                <span style="color:var(--gray); font-style:italic;">Non renseigné</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <a href="{{ route('detenteurs.show', $detenteur->id_detenteur) }}" class="btn-view" target="_blank">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>Aucun détenteur validé</h3>
                            <p>Aucun détenteur n'a été validé pour le moment.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div style="color:var(--gray);">
                <i class="fas fa-info-circle"></i>
                <strong>{{ $detenteurs->count() }}</strong> détenteur(s) affiché(s) sur {{ $detenteurs->total() }}
            </div>
            <div class="pagination">
                {{ $detenteurs->links() }}
            </div>
        </div>
    </div>
</div>

<script>
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


