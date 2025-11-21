@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#3b82f6; --secondary:#f97316; --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    .page-bg { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height:100vh; padding:2rem 1rem; }
    .container-max { max-width:1600px; margin:0 auto; }
    .page-header { background:#fff; border-radius:20px; padding:2rem; margin-bottom:2rem; box-shadow:0 4px 20px rgba(0,0,0,.05); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .header-left { display:flex; align-items:center; gap:1rem; }
    .header-icon { width:60px; height:60px; background:linear-gradient(135deg, var(--primary), var(--secondary)); border-radius:15px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; color:#fff; }
    .page-header h1 { font-size:2rem; font-weight:800; color:var(--dark); margin-bottom:.25rem; }
    .page-header p { color:var(--gray); font-size:.95rem; }
    .btn { padding:.75rem 1.5rem; border-radius:10px; font-weight:600; cursor:pointer; transition:all .3s ease; border:none; display:inline-flex; align-items:center; gap:.5rem; text-decoration:none; font-size:.95rem; }
    .btn-primary { background:linear-gradient(135deg, var(--primary), var(--secondary)); color:#fff; box-shadow:0 4px 15px rgba(59,130,246,.3); }
    .btn-primary:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(59,130,246,.4); }
    .btn-secondary { background:#fff; color:var(--gray); border:2px solid #e2e8f0; }
    .btn-secondary:hover { background:var(--light); border-color:var(--gray); }
    .btn-success { background:var(--success); color:#fff; }
    .btn-warning { background:var(--warning); color:#fff; }
    .btn-danger { background:var(--danger); color:#fff; }
    .btn-sm { padding:.5rem 1rem; font-size:.875rem; }
    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(250px,1fr)); gap:1.5rem; margin-bottom:2rem; }
    .stat-card { background:#fff; border-radius:16px; padding:1.75rem; box-shadow:0 4px 15px rgba(0,0,0,.05); transition:all .3s ease; position:relative; overflow:hidden; }
    .stat-card::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; background:linear-gradient(180deg, var(--primary), var(--secondary)); }
    .stat-card:hover { transform:translateY(-5px); box-shadow:0 8px 30px rgba(0,0,0,.1); }
    .stat-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
    .stat-title { color:var(--gray); font-size:.9rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; }
    .stat-icon { width:45px; height:45px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; }
    .stat-icon.blue { background:rgba(59,130,246,.1); color:var(--primary); }
    .stat-icon.green { background:rgba(16,185,129,.1); color:var(--success); }
    .stat-icon.orange { background:rgba(249,115,22,.1); color:var(--secondary); }
    .stat-number { font-size:2.5rem; font-weight:800; color:var(--dark); margin-bottom:.25rem; }
    .stat-change { font-size:.85rem; color:var(--success); }
    .filter-section { background:#fff; border-radius:16px; padding:1.5rem; margin-bottom:2rem; box-shadow:0 4px 15px rgba(0,0,0,.05); }
    .filter-grid { display:grid; grid-template-columns:2fr 1fr 1fr auto; gap:1rem; align-items:end; }
    .form-group { display:flex; flex-direction:column; gap:.5rem; }
    .form-group label { font-weight:600; color:var(--dark); font-size:.9rem; }
    .form-control { padding:.75rem 1rem; border:2px solid #e2e8f0; border-radius:10px; font-size:.95rem; transition:all .3s ease; }
    .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    .create-user-section { background:linear-gradient(135deg, rgba(59,130,246,.05), rgba(249,115,22,.05)); border:2px solid rgba(59,130,246,.2); border-radius:16px; padding:2rem; margin-bottom:2rem; }
    .create-user-header { display:flex; align-items:center; gap:.75rem; margin-bottom:1.5rem; }
    .create-user-header h3 { font-size:1.3rem; font-weight:700; color:var(--dark); }
    .create-user-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:1rem; }
    .main-card { background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,.05); overflow:hidden; }
    .card-header { background:linear-gradient(135deg, var(--primary), var(--secondary)); color:#fff; padding:1.5rem 2rem; display:flex; justify-content:space-between; align-items:center; }
    .card-header h2 { font-size:1.3rem; font-weight:700; display:flex; align-items:center; gap:.75rem; }
    .table-container { overflow-x:auto; }
    table { width:100%; border-collapse:collapse; }
    thead { background:linear-gradient(135deg, #f8fafc, #e2e8f0); }
    thead th { padding:1.25rem 1rem; text-align:left; font-weight:700; color:var(--dark); font-size:.875rem; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; }
    tbody tr { border-bottom:1px solid #f1f5f9; transition:all .2s ease; }
    tbody tr:hover { background:#f8fafc; }
    tbody td { padding:1.25rem 1rem; color:var(--gray); vertical-align:middle; }
    .user-info { display:flex; align-items:center; gap:1rem; }
    .user-avatar { width:45px; height:45px; border-radius:50%; background:linear-gradient(135deg, var(--primary), var(--secondary)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1.1rem; flex-shrink:0; }
    .user-details { display:flex; flex-direction:column; gap:.25rem; }
    .user-name { font-weight:700; color:var(--dark); font-size:.95rem; }
    .user-email { font-size:.85rem; color:var(--gray); }
    .badge { display:inline-flex; align-items:center; gap:.5rem; padding:.4rem 1rem; border-radius:20px; font-size:.85rem; font-weight:600; }
    .badge-admin { background:rgba(239,68,68,.1); color:var(--danger); }
    .badge-gestionnaire { background:rgba(59,130,246,.1); color:var(--primary); }
    .badge-visiteur { background:rgba(100,116,139,.1); color:var(--gray); }
    .action-buttons { display:flex; gap:.5rem; flex-wrap:wrap; }
    .btn-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; border:none; cursor:pointer; transition:all .3s ease; font-size:.9rem; }
    .btn-icon.edit { background:rgba(59,130,246,.1); color:var(--primary); }
    .btn-icon.reset { background:rgba(100,116,139,.1); color:var(--gray); }
    .btn-icon.activate { background:rgba(16,185,129,.1); color:var(--success); }
    .btn-icon.deactivate { background:rgba(245,158,11,.1); color:var(--warning); }
    .btn-icon.delete { background:rgba(239,68,68,.1); color:var(--danger); }
    .btn-icon:hover { transform:scale(1.1); }
    select.form-control { cursor:pointer; background:#fff; }
    .table-footer { padding:1.5rem 2rem; background:#f8fafc; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .pagination { display:flex; gap:.5rem; }
    .page-link { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; border:2px solid #e2e8f0; background:#fff; color:var(--gray); cursor:pointer; transition:all .3s ease; text-decoration:none; }
    .page-link:hover, .page-link.active { background:var(--primary); color:#fff; border-color:var(--primary); }
    @media (max-width:1024px){ .filter-grid{ grid-template-columns:1fr 1fr; } .create-user-grid{ grid-template-columns:1fr; } }
    @media (max-width:768px){ .page-bg{ padding:1rem .5rem; } .page-header{ flex-direction:column; align-items:flex-start; } .stats-grid{ grid-template-columns:1fr; } .filter-grid{ grid-template-columns:1fr; } .table-container{ font-size:.875rem; } .action-buttons{ flex-direction:column; } .btn-icon{ width:100%; height:40px; border-radius:8px; justify-content:flex-start; padding:0 1rem; } }
</style>
@endpush

@section('content')
<div class="page-bg">
    <div class="container-max">
        <div class="page-header">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div>
                    <h1>Gestion des Utilisateurs</h1>
                    <p>Administration et contrôle des accès à la plateforme</p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Retour Dashboard
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Utilisateurs</span>
                    <div class="stat-icon blue">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $utilisateurs->total() }}</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> Actifs
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Administrateurs</span>
                    <div class="stat-icon orange">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $utilisateurs->getCollection()->where('type_utilisateur','admin')->count() }}</div>
                <div class="stat-change">
                    <i class="fas fa-minus"></i> Stable
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Gestionnaires</span>
                    <div class="stat-icon green">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $utilisateurs->getCollection()->where('type_utilisateur','gestionnaire')->count() }}</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> Actifs
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Visiteurs</span>
                    <div class="stat-icon blue">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $utilisateurs->getCollection()->where('type_utilisateur','visiteur')->count() }}</div>
                <div class="stat-change">
                    <i class="fas fa-arrow-up"></i> Actifs
                </div>
            </div>
        </div>

        <!-- Create User Section -->
        <div class="create-user-section">
            <div class="create-user-header">
                <i class="fas fa-user-plus" style="color: var(--primary); font-size: 1.5rem;"></i>
                <h3>Créer un Nouvel Utilisateur</h3>
            </div>
            <form method="POST" action="{{ route('admin.utilisateurs.creer') }}" class="create-user-grid">
                @csrf
                <div class="form-group">
                    <label>Nom complet <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Jean Dupont" required>
                </div>
                <div class="form-group">
                    <label>Email <span style="color: var(--danger);">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="jean@example.com" required>
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" class="form-control" placeholder="+226 XX XX XX XX">
                </div>
                <div class="form-group">
                    <label>Type d'utilisateur <span style="color: var(--danger);">*</span></label>
                    <select name="type_utilisateur" class="form-control" required>
                        <option value="visiteur" selected>Visiteur</option>
                        <option value="gestionnaire">Gestionnaire</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <div class="form-group" style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn btn-success" style="width: 100%;">
                        <i class="fas fa-plus"></i>
                        Créer l'utilisateur
                    </button>
                </div>
            </form>
            <p style="margin-top: 1rem; color: var(--gray); font-size: 0.9rem;">
                <i class="fas fa-info-circle"></i> Un mot de passe temporaire sera généré automatiquement et envoyé par email.
            </p>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" class="filter-grid">
                <div class="form-group">
                    <label><i class="fas fa-search"></i> Rechercher</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nom, email ou téléphone...">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-filter"></i> Type d'utilisateur</label>
                    <select name="type" class="form-control">
                        <option value="">Tous les types</option>
                        <option value="admin" @selected(request('type')==='admin')>Administrateur</option>
                        <option value="gestionnaire" @selected(request('type')==='gestionnaire')>Gestionnaire</option>
                        <option value="visiteur" @selected(request('type')==='visiteur')>Visiteur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-toggle-on"></i> Statut</label>
                    <select class="form-control">
                        <option value="">Tous</option>
                        <option value="actif">Actifs</option>
                        <option value="inactif">Inactifs</option>
                    </select>
                </div>
                <div style="display: flex; align-items: flex-end; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Main Table -->
        <div class="main-card">
            <div class="card-header">
                <h2>
                    <i class="fas fa-list"></i>
                    Liste des Utilisateurs
                </h2>
                <a href="{{ route('admin.utilisateurs.export', request()->only('q','type')) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-download"></i>
                    Exporter CSV
                </a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Utilisateur</th>
                            <th><i class="fas fa-tag"></i> Type</th>
                            <th><i class="fas fa-shield-alt"></i> Rôles</th>
                            <th><i class="fas fa-phone"></i> Contact</th>
                            <th><i class="fas fa-toggle-on"></i> Statut</th>
                            <th style="text-align: center;"><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($utilisateurs as $u)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ substr($u->name, 0, 2) }}</div>
                                    <div class="user-details">
                                        <span class="user-name">{{ $u->name }}</span>
                                        <span class="user-email">{{ $u->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('admin.utilisateurs.type', $u) }}" method="POST">
                                    @csrf
                                    <select name="type_utilisateur" class="form-control" style="padding: 0.5rem;" onchange="this.form.submit()">
                                        <option value="admin" @selected($u->type_utilisateur==='admin')>Admin</option>
                                        <option value="gestionnaire" @selected($u->type_utilisateur==='gestionnaire')>Gestionnaire</option>
                                        <option value="visiteur" @selected($u->type_utilisateur==='visiteur')>Visiteur</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                @if($u->type_utilisateur === 'admin')
                                    <span class="badge badge-admin">Super Admin</span>
                                @elseif($u->type_utilisateur === 'gestionnaire')
                                    <span class="badge badge-gestionnaire">Validation, Édition</span>
                                @else
                                    <span class="badge badge-visiteur">Consultation</span>
                                @endif
                            </td>
                            <td>{{ $u->telephone ?: '—' }}</td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                                        <i class="fas fa-check-circle"></i> Actif
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                                        <i class="fas fa-ban"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons" style="justify-content: center;">
                                    <form action="{{ route('admin.utilisateurs.attribuer-role', $u) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <select name="role_id" class="form-control" style="padding: 0.5rem; margin-bottom: 0.5rem;" onchange="this.form.submit()">
                                            @foreach($roles as $r)
                                                <option value="{{ $r->id_role }}">{{ $r->nom_role }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                    
                                    <form action="{{ route('admin.utilisateurs.reset-mdp', $u) }}" method="POST" style="display: inline;" onsubmit="return confirm('Réinitialiser le mot de passe ?')">
                                        @csrf
                                        <button class="btn-icon reset" title="Réinitialiser mot de passe">
                                            <i class="fas fa-key"></i>
                                        </button>
                                    </form>

                                    @if(!$u->is_active)
                                        <form action="{{ route('admin.utilisateurs.activer', $u) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button class="btn-icon activate" title="Activer">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.utilisateurs.desactiver', $u) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button class="btn-icon deactivate" title="Désactiver">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.utilisateurs.supprimer', $u) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn-icon delete" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div style="color: var(--gray);">
                    <i class="fas fa-info-circle"></i>
                    <strong>{{ $utilisateurs->count() }}</strong> utilisateur(s) affiché(s)
                </div>
                <div class="pagination">
                    {{ $utilisateurs->links() }}
                </div>
            </div>
        </div>
@endsection





