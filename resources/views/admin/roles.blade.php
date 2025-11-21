@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#3b82f6; --secondary:#f97316; --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    .page-bg { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height:100vh; padding:2rem 1rem; }
    .container-max { max-width:1600px; margin:0 auto; }
    .welcome-header { background:linear-gradient(135deg, var(--primary), var(--secondary)); border-radius:20px; padding:2rem; margin-bottom:2rem; color:#fff; box-shadow:0 10px 40px rgba(59,130,246,.3); position:relative; overflow:hidden; }
    .welcome-content { position:relative; z-index:2; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .welcome-title { display:flex; align-items:center; gap:1rem; }
    .header-icon { width:56px; height:56px; border-radius:14px; background:rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
    .welcome-actions { display:flex; gap:.75rem; }
    .btn { padding:.75rem 1.5rem; border-radius:12px; font-weight:600; cursor:pointer; transition:all .3s ease; border:none; display:inline-flex; align-items:center; gap:.5rem; text-decoration:none; font-size:.95rem; }
    .btn-white { background:#fff; color:var(--primary); box-shadow:0 4px 15px rgba(0,0,0,.1); }
    .btn-white:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.15); }
    .btn-secondary { background:#fff; color:var(--gray); border:2px solid #e2e8f0; }
    .btn-secondary:hover { background:var(--light); border-color:var(--gray); }
    .btn-success { background:var(--success); color:#fff; }
    .btn-danger { background:var(--danger); color:#fff; }

    /* Create Role Card */
    .card { background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,.05); overflow:hidden; }
    .card-inner { padding:1.5rem; }
    .section-title { font-size:1.2rem; font-weight:700; color:var(--dark); margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
    .form-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:1rem; }
    .form-group { display:flex; flex-direction:column; gap:.5rem; }
    .form-group label { font-weight:600; color:var(--dark); font-size:.9rem; }
    .form-control { padding:.75rem 1rem; border:2px solid #e2e8f0; border-radius:10px; font-size:.95rem; transition:all .3s ease; background:#fff; }
    .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,.1); }

    /* Main Table */
    .main-card { background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,.05); overflow:hidden; }
    .card-header { background:linear-gradient(135deg, var(--primary), var(--secondary)); color:#fff; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center; }
    .card-header h2 { font-size:1.1rem; font-weight:700; display:flex; align-items:center; gap:.5rem; margin:0; }
    .table-container { overflow-x:auto; }
    table { width:100%; border-collapse:collapse; }
    thead { background:linear-gradient(135deg, #f8fafc, #e2e8f0); }
    thead th { padding:1rem; text-align:left; font-weight:700; color:var(--dark); font-size:.85rem; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; }
    tbody tr { border-bottom:1px solid #f1f5f9; transition:all .2s ease; }
    tbody tr:hover { background:#f8fafc; }
    tbody td { padding:1rem; color:var(--gray); vertical-align:middle; }
    .badge { display:inline-flex; align-items:center; gap:.5rem; padding:.35rem .9rem; border-radius:20px; font-size:.8rem; font-weight:600; }
    .badge-gray { background:rgba(100,116,139,.1); color:var(--gray); }
    .table-footer { padding:1rem 1.5rem; background:#f8fafc; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }

    .alert { padding: .9rem 1rem; border-radius:10px; font-weight:600; margin-bottom:1rem; }
    .alert-success { background:rgba(16,185,129,.1); color:var(--success); border:2px solid rgba(16,185,129,.25); }
    .alert-danger { background:rgba(239,68,68,.1); color:var(--danger); border:2px solid rgba(239,68,68,.25); }

    @media (max-width:768px){ .page-bg{ padding:1rem .5rem; } .welcome-content{ flex-direction:column; align-items:flex-start; } }
</style>
@endpush

@section('content')
<div class="page-bg">
    <div class="container-max">
        <div class="welcome-header">
            <div class="welcome-content">
                <div class="welcome-title">
                    <div class="header-icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h2 style="margin:0; font-size:1.6rem; font-weight:800;">Gestion des Rôles</h2>
                        <div style="opacity:.9;">Configuration des permissions et groupes d'accès</div>
                    </div>
                </div>
                <div class="welcome-actions">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-white">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div>
        @endif

        <!-- Create Role Card -->
        <div class="card" style="margin-bottom:1.5rem;">
            <div class="card-inner">
                <div class="section-title"><i class="fas fa-plus-circle" style="color:var(--primary);"></i> Créer un nouveau rôle</div>
                <form action="{{ route('admin.roles.creer') }}" method="POST" class="form-grid">
                    @csrf
                    <div class="form-group">
                        <label>Nom du rôle <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="nom_role" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Optionnel">
                    </div>
                    <div class="form-group" style="display:flex; align-items:flex-end;">
                        <button type="submit" class="btn btn-success" style="width:100%;">
                            <i class="fas fa-save"></i> Créer le rôle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="main-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Liste des Rôles</h2>
                <span class="badge badge-gray"><i class="fas fa-hashtag"></i> {{ $roles->total() }} total</span>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-tag"></i> Rôle</th>
                            <th><i class="fas fa-align-left"></i> Description</th>
                            <th><i class="fas fa-users"></i> Utilisateurs</th>
                            <th style="text-align:center;"><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td style="color:var(--dark); font-weight:700;">{{ $role->nom_role }}</td>
                            <td>{{ $role->description ?: '—' }}</td>
                            <td>
                                <span class="badge badge-gray"><i class="fas fa-user"></i> {{ $role->users_count }}</span>
                            </td>
                            <td>
                                <div style="display:flex; gap:.5rem; justify-content:center; flex-wrap:wrap;">
                                    @if($role->users_count == 0)
                                        <form action="{{ route('admin.roles.supprimer', $role) }}" method="POST" onsubmit="return confirm('Supprimer ce rôle ?')" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger" style="padding:.5rem .9rem; border-radius:10px;">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge badge-gray"><i class="fas fa-lock"></i> En cours d'utilisation</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <div style="color:var(--gray);"><i class="fas fa-info-circle"></i> {{ $roles->count() }} rôle(s) affiché(s)</div>
                <div>
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



