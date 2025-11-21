@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root { --primary:#FF0000; --secondary:#00AA00; --success:#10b981; --warning:#f59e0b; --danger:#ef4444; --dark:#0f172a; --light:#f8fafc; --gray:#64748b; }
    
    body { font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; }
    .page-bg { background:linear-gradient(135deg,#f8fafc 0%,#e2e8f0 100%); min-height:100vh; padding:2rem 1rem; }
    .container-max { max-width:1600px; margin:0 auto; }

    /* Header */
    .welcome-header { background:linear-gradient(135deg,var(--primary),var(--secondary)); border-radius:20px; padding:2rem; margin-bottom:2rem; color:#fff; box-shadow:0 10px 40px rgba(59,130,246,.3); position:relative; overflow:hidden; }
    .welcome-header::before { content:''; position:absolute; top:-50%; right:-10%; width:400px; height:400px; background:radial-gradient(circle,rgba(255,255,255,.1) 0%,transparent 70%); border-radius:50%; }
    .welcome-content { position:relative; z-index:2; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .welcome-title { display:flex; align-items:center; gap:1rem; }
    .header-icon { width:56px; height:56px; border-radius:14px; background:rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
    .btn { padding:.75rem 1.5rem; border-radius:12px; font-weight:600; cursor:pointer; transition:all .3s ease; border:none; display:inline-flex; align-items:center; gap:.5rem; text-decoration:none; font-size:.95rem; }
    .btn-white { background:#fff; color:var(--primary); box-shadow:0 4px 15px rgba(0,0,0,.1); }
    .btn-white:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.15); }

    /* KPI Grid */
    .kpi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:1.5rem; }
    .kpi-card { background:#fff; border-radius:16px; padding:1.25rem; box-shadow:0 4px 20px rgba(0,0,0,.05); position:relative; transition:all .3s ease; }
    .kpi-card::before { content:''; position:absolute; top:0; left:0; width:100%; height:4px; background:linear-gradient(90deg,var(--primary),var(--secondary)); border-radius:16px 16px 0 0; }
    .kpi-card:hover { transform:translateY(-5px); box-shadow:0 8px 30px rgba(0,0,0,.1); }
    .kpi-title { font-size:.85rem; color:var(--gray); font-weight:700; text-transform:uppercase; margin-bottom:.5rem; }
    .kpi-value { font-size:2rem; font-weight:800; color:var(--dark); }
    .kpi-badges { display:flex; gap:.5rem; margin-top:.75rem; flex-wrap:wrap; }

    /* Badges */
    .tag-badge { display:inline-flex; align-items:center; gap:.35rem; padding:.3rem .75rem; border-radius:20px; font-size:.75rem; font-weight:600; }
    .tag-badge-gray { background:rgba(100,116,139,.1); color:var(--gray); }
    .tag-badge-green { background:rgba(16,185,129,.1); color:var(--success); }
    .tag-badge-yellow { background:rgba(245,158,11,.1); color:var(--warning); }
    .tag-badge-blue { background:rgba(59,130,246,.1); color:var(--primary); }

    /* Main Card */
    .main-card { background:#fff; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,.05); overflow:hidden; }
    .card-header { background:linear-gradient(135deg,var(--primary),var(--secondary)); color:#fff; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center; }
    .card-header h2 { font-size:1.15rem; font-weight:700; display:flex; align-items:center; gap:.5rem; margin:0; }

    /* Table */
    .table-container { overflow-x:auto; }
    table { width:100%; border-collapse:collapse; }
    thead { background:linear-gradient(135deg,#f8fafc,#e2e8f0); }
    thead th { padding:1rem; text-align:left; font-weight:700; color:var(--dark); font-size:.85rem; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #e2e8f0; white-space:nowrap; }
    tbody tr { border-bottom:1px solid #f1f5f9; transition:all .2s ease; }
    tbody tr:hover { background:#f8fafc; }
    tbody td { padding:1rem; color:var(--gray); vertical-align:middle; }
    .code-cell { font-family:'Courier New',monospace; font-size:.9rem; font-weight:600; color:var(--primary); }
    .name-cell { color:var(--dark); font-weight:700; }

    /* Table Footer */
    .table-footer { padding:1rem 1.5rem; background:#f8fafc; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; }
    .pagination { display:flex; gap:.5rem; align-items:center; }
    .page-link { display:inline-flex; align-items:center; justify-content:center; min-width:36px; height:36px; padding:0 .75rem; border-radius:8px; border:2px solid #e2e8f0; background:#fff; color:var(--gray); text-decoration:none; transition:all .3s ease; }
    .page-link:hover { background:var(--primary); color:#fff; border-color:var(--primary); }
    .page-link.active { background:var(--primary); border-color:var(--primary); color:#fff; }

    /* Responsive */
    @media (max-width:768px) { 
        .page-bg { padding:1rem .5rem; } 
        .welcome-content { flex-direction:column; align-items:flex-start; }
        .kpi-grid { grid-template-columns:1fr; }
        .table-footer { flex-direction:column; align-items:center; }
    }
</style>
@endpush

@section('content')
<div class="page-bg patrimoine-page">
    <div class="container-max">
        <div class="welcome-header">
            <div class="welcome-content">
                <div class="welcome-title">
                    <div class="header-icon"><i class="fas fa-landmark"></i></div>
                    <div>
                        <h2 style="margin:0; font-size:1.6rem; font-weight:800;">Gestion des Patrimoines</h2>
                        <div style="opacity:.9;">Liste, domaines et indicateurs clés</div>
                    </div>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-white"><i class="fas fa-arrow-left"></i> Dashboard</a>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="kpi-grid">
            <!-- Total -->
            <div class="kpi-card" title="Total éléments patrimoniaux">
                <div class="kpi-title">Total éléments</div>
                <div class="kpi-value">{{ $patrimoines->total() }}</div>
                <div class="kpi-badges">
                    <span class="tag-badge tag-badge-green">
                        <i class="fas fa-check-circle"></i> Actifs
                    </span>
                </div>
            </div>

            @php $domaines = ['CPNU','PSREF','ADS','SFAT','TEO']; @endphp
            @foreach($domaines as $d)
            <div class="kpi-card">
                <div class="kpi-title">{{ $d }}</div>
                <div class="kpi-value" style="font-size:1.5rem;">{{ $parDomaine[$d] ?? 0 }}</div>
                <div class="kpi-badges">
                    <span class="tag-badge tag-badge-gray">
                        <i class="fas fa-layer-group"></i> éléments
                    </span>
                    <span class="tag-badge tag-badge-blue">
                        <i class="fas fa-users"></i> {{ $detenteursParDomaine[$d] ?? 0 }} détenteurs
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Main Table -->
        <div class="main-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Liste des Patrimoines</h2>
                <span class="tag-badge tag-badge-gray" style="background:rgba(255,255,255,.2); color:#fff;">
                    <i class="fas fa-hashtag"></i> {{ $patrimoines->total() }} total
                </span>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-barcode"></i> Code</th>
                            <th><i class="fas fa-signature"></i> Nom</th>
                            <th><i class="fas fa-sitemap"></i> Domaine</th>
                            <th><i class="fas fa-folder"></i> Catégorie</th>
                            <th><i class="fas fa-check-circle"></i> Statut</th>
                            <th><i class="fas fa-users"></i> Détenteurs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patrimoines as $p)
                        <tr>
                            <td class="code-cell">{{ $p->domaine }}-{{ $p->numero_element }}</td>
                            <td class="name-cell">{{ $p->nom }}</td>
                            <td>
                                <span class="tag-badge tag-badge-gray">{{ $p->domaine }}</span>
                            </td>
                            <td>{{ optional($p->categorie)->nom_complet ?: '—' }}</td>
                            <td>
                                @php $isInscrit = strtolower($p->status) === 'inscrit'; @endphp
                                <span class="tag-badge {{ $isInscrit ? 'tag-badge-green' : 'tag-badge-yellow' }}">
                                    <i class="fas fa-{{ $isInscrit ? 'check' : 'clock' }}"></i> {{ $p->status }}
                                </span>
                            </td>
                            <td>{{ $p->detenteurs_count ?? 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div style="color:var(--gray);">
                    <i class="fas fa-info-circle"></i> {{ $patrimoines->count() }} élément(s) affiché(s)
                </div>
                <div class="pagination">
                    {{ $patrimoines->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Animation au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.kpi-card, .main-card');
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
</script>
@endsection







