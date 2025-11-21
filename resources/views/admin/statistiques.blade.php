@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    :root {
        --primary:#3b82f6; --secondary:#f97316; --success:#10b981; --warning:#f59e0b;
            --danger:#ef4444; --dark:#0f172a; --light:#f8fafc; --gray:#94a3b8;
        --indigo:#6366f1; --purple:#8b5cf6;
    }
    body .page-bg {
        background: #ffffff;
        min-height:100vh;
        padding:1.5rem 1rem;
        color:#0f172a;
    }
    .container-max { max-width:1800px; margin:0 auto; }
    .top-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; gap:1rem; flex-wrap:wrap; }
    .page-title { display:flex; align-items:center; gap:1rem; }
    .title-icon { width:56px; height:56px; border-radius:16px; background:linear-gradient(135deg,var(--primary),var(--indigo)); display:flex; align-items:center; justify-content:center; font-size:1.6rem; box-shadow:0 10px 30px rgba(59,130,246,.35); }
    .page-title h1 { font-size:2.1rem; font-weight:800; color:#f8fafc; }
    .page-subtitle { color:#94a3b8; font-size:.95rem; }
    .btn-back { background:linear-gradient(135deg,#1e293b 0%, #334155 100%); color:#e2e8f0; padding:.75rem 1.5rem; border-radius:12px; text-decoration:none; display:inline-flex; align-items:center; gap:.5rem; font-weight:600; border:1px solid rgba(255,255,255,.15); transition:all .3s ease; }
    .btn-back:hover { transform:translateY(-2px); box-shadow:0 15px 35px rgba(0,0,0,.35); }

    .summary-cards { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:2rem; }
    .summary-card { background:linear-gradient(135deg,#1e293b 0%,#334155 100%); border-radius:18px; padding:1.5rem; border:1px solid rgba(255,255,255,.08); position:relative; overflow:hidden; }
    .summary-card::after { content:''; position:absolute; top:-30px; right:-30px; width:120px; height:120px; background:radial-gradient(circle,rgba(59,130,246,.15) 0%, transparent 60%); border-radius:50%; }
    .summary-label { font-size:.8rem; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8; font-weight:600; }
    .summary-value { font-size:2.1rem; font-weight:900; color:#f8fafc; margin-top:.35rem; }
    .summary-icon { position:absolute; bottom:1rem; right:1rem; font-size:2rem; opacity:.15; color:#f8fafc; }

    .charts-hero { display:grid; grid-template-columns:2fr 1fr; gap:1.5rem; margin-bottom:2rem; }
    .chart-stack { display:flex; flex-direction:column; gap:1.5rem; }
    .chart-card { background:linear-gradient(135deg,#1e293b 0%,#334155 100%); border-radius:22px; border:1px solid rgba(255,255,255,.08); overflow:hidden; position:relative; transition:all .3s ease; }
    .chart-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,var(--primary),var(--purple)); }
    .chart-card:hover { transform:translateY(-4px); box-shadow:0 25px 45px rgba(0,0,0,.35); border-color:rgba(59,130,246,.35); }
    .chart-header { display:flex; justify-content:space-between; align-items:center; padding:1.5rem; border-bottom:1px solid rgba(255,255,255,.08); }
    .chart-title { display:flex; align-items:center; gap:.75rem; font-weight:700; font-size:1.1rem; color:#f8fafc; }
    .chart-icon { width:38px; height:38px; border-radius:12px; background:rgba(59,130,246,.2); display:flex; align-items:center; justify-content:center; color:var(--primary); }
    .chart-badge { padding:.35rem .9rem; border-radius:10px; font-size:.75rem; font-weight:600; background:rgba(16,185,129,.25); color:var(--success); }
    .chart-body { padding:1.5rem 2rem; min-height:240px; }

    .kpi-section { margin-top:2rem; }
    .section-header { display:flex; align-items:center; gap:.75rem; margin-bottom:1.5rem; }
    .section-header h2 { font-size:1.5rem; font-weight:700; color:#f8fafc; }
    .section-line { flex:1; height:2px; background:linear-gradient(90deg,rgba(59,130,246,.35),transparent); }

    .kpi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); gap:1.5rem; }
    .kpi-card { background:linear-gradient(135deg,#1e293b 0%,#334155 100%); border-radius:22px; padding:2rem; border:1px solid rgba(255,255,255,.08); position:relative; overflow:hidden; transition:all .3s ease; }
    .kpi-card::before { content:''; position:absolute; top:0; left:0; width:100%; height:5px; background:linear-gradient(90deg,var(--primary),var(--secondary)); }
    .kpi-card:hover { transform:translateY(-4px); border-color:rgba(59,130,246,.4); }
    .kpi-title { font-size:.85rem; letter-spacing:.5px; text-transform:uppercase; color:#94a3b8; font-weight:700; display:flex; align-items:center; gap:.5rem; }
    .kpi-value { font-size:2.6rem; font-weight:900; color:#f8fafc; margin-top:.5rem; }
    .kpi-badges { margin-top:1rem; display:flex; flex-wrap:wrap; gap:.5rem; }
    .badge { display:inline-flex; align-items:center; gap:.4rem; padding:.45rem 1rem; border-radius:12px; font-size:.8rem; font-weight:600; }
    .badge-green { background:rgba(16,185,129,.18); color:var(--success); }
    .badge-yellow { background:rgba(245,158,11,.18); color:var(--warning); }
    .badge-blue { background:rgba(59,130,246,.18); color:var(--primary); }
    .badge-red { background:rgba(239,68,68,.18); color:var(--danger); }

    .domain-stats { margin-top:1rem; display:grid; grid-template-columns:repeat(2,1fr); gap:1rem; }
    .domain-stat-item { background:rgba(255,255,255,.05); border-radius:12px; padding:1rem; }
    .domain-stat-label { font-size:.75rem; text-transform:uppercase; color:#94a3b8; letter-spacing:.5px; }
    .domain-stat-value { font-size:1.8rem; font-weight:800; color:#f8fafc; margin-top:.25rem; }
    .progress-bar { margin-top:1rem; width:100%; height:10px; background:rgba(255,255,255,.08); border-radius:999px; overflow:hidden; }
    .progress-fill { height:100%; background:linear-gradient(90deg,var(--primary),var(--success)); border-radius:999px; }

    .footer-info { text-align:center; margin-top:2rem; padding:1.25rem; background:rgba(255,255,255,.05); border-radius:14px; color:#94a3b8; font-size:.9rem; }

    @media (max-width:1200px){ .charts-hero{ grid-template-columns:1fr; } }
    @media (max-width:768px){ .top-bar{ flex-direction:column; align-items:flex-start; } .summary-cards{ grid-template-columns:repeat(2,1fr); } }
    @media (max-width:540px){ .summary-cards{ grid-template-columns:1fr; } }
</style>
@endpush

@php
    $totalDemandes = (int)($kpiDemandes['total'] ?? 0);
    $totalDetenteurs = (int)($kpiDetenteurs['total'] ?? 0);
    $validationRate = $totalDemandes ? round(($kpiDemandes['validees'] ?? 0) / max($totalDemandes,1) * 100, 1) : 0;
    $elementsArray = $elementsParDomaine instanceof \Illuminate\Support\Collection ? $elementsParDomaine->toArray() : ($elementsParDomaine ?? []);
    $elementsTotal = array_sum($elementsArray);
    $enCours = (int)($kpiDemandes['en_cours'] ?? 0);
    $enAttente = (int)($kpiDemandes['en_attente'] ?? 0);
@endphp

@section('content')
<div class="page-bg">
    <div class="container-max">
        <div class="top-bar">
            <div class="page-title">
                <div class="title-icon"><i class="fas fa-chart-line"></i></div>
                <div>
                    <h1>Page Statistiques</h1>
                    <div class="page-subtitle">Vue analytique des demandes & détenteurs</div>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Retour Dashboard</a>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-label">Total demandes</div>
                <div class="summary-value">{{ $totalDemandes }}</div>
                <i class="fas fa-inbox summary-icon"></i>
            </div>
            <div class="summary-card">
                <div class="summary-label">Détenteurs</div>
                <div class="summary-value">{{ $totalDetenteurs }}</div>
                <i class="fas fa-users summary-icon"></i>
            </div>
            <div class="summary-card">
                <div class="summary-label">Taux de validation</div>
                <div class="summary-value">{{ $validationRate }}%</div>
                <i class="fas fa-check-circle summary-icon"></i>
            </div>
            <div class="summary-card">
                <div class="summary-label">Éléments recensés</div>
                <div class="summary-value">{{ $elementsTotal }}</div>
                <i class="fas fa-database summary-icon"></i>
            </div>
        </div>

        <div class="charts-hero">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">
                        <div class="chart-icon"><i class="fas fa-chart-pie"></i></div>
                        Répartition des demandes
                    </div>
                    <span class="chart-badge"><i class="fas fa-info-circle"></i> Statut global</span>
                </div>
                <div class="chart-body">
                    <canvas id="chartDemandes" height="220"></canvas>
                </div>
            </div>

            <div class="chart-stack">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <div class="chart-icon"><i class="fas fa-bolt"></i></div>
                            Indicateurs clés
                        </div>
                    </div>
                    <div class="chart-body" style="min-height:180px;">
                        <div class="kpi-badges" style="margin-top:0;">
                            <span class="badge badge-yellow">⏳ {{ $enAttente }} en attente</span>
                            <span class="badge badge-blue">🔄 {{ $enCours }} en cours</span>
                            <span class="badge badge-green">✅ {{ $kpiDemandes['validees'] ?? 0 }} validées</span>
                            <span class="badge badge-red">❌ {{ $kpiDemandes['rejetees'] ?? 0 }} rejetées</span>
                        </div>
                    </div>
                </div>
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">
                            <div class="chart-icon"><i class="fas fa-certificate"></i></div>
                            Vérification détenteurs
                        </div>
                    </div>
                    <div class="chart-body" style="min-height:180px;">
                        <p style="margin:0; color:#94a3b8;">Vérifiés : <strong style="color:#f8fafc;">{{ $kpiDetenteurs['verifies'] ?? 0 }}</strong></p>
                        <p style="margin-top:.35rem; color:#94a3b8;">En attente : <strong style="color:#f8fafc;">{{ max(0, $totalDetenteurs - ($kpiDetenteurs['verifies'] ?? 0)) }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <div class="chart-icon"><i class="fas fa-chart-column"></i></div>
                    Éléments vs Détenteurs par domaine
                </div>
                <span class="chart-badge"><i class="fas fa-layer-group"></i> Comparatif</span>
            </div>
            <div class="chart-body">
                <canvas id="chartDomaines" height="280"></canvas>
            </div>
        </div>

        <div class="kpi-section">
            <div class="section-header">
                <h2>Détails par domaine</h2>
                <div class="section-line"></div>
            </div>
            <div class="kpi-grid">
                @foreach($domaines as $domaine)
                    @php
                        $el = (int)($elementsParDomaine[$domaine] ?? 0);
                        $det = (int)($detenteursParDomaine[$domaine] ?? 0);
                        $pct = $el ? min(100, round(($det / max($el,1))*100)) : 0;
                    @endphp
                    <div class="kpi-card">
                        <div class="kpi-title"><i class="fas fa-folders"></i> {{ $domaine }}</div>
                        <div class="kpi-value">{{ $el }}</div>
                        <div class="domain-stats">
                            <div class="domain-stat-item">
                                <div class="domain-stat-label">Éléments</div>
                                <div class="domain-stat-value">{{ $el }}</div>
                            </div>
                            <div class="domain-stat-item">
                                <div class="domain-stat-label">Détenteurs</div>
                                <div class="domain-stat-value">{{ $det }}</div>
                            </div>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $pct }}%;"></div>
                        </div>
                        <div class="kpi-badges" style="margin-top:1rem;">
                            <span class="badge badge-blue"><i class="fas fa-database"></i> {{ $el }} éléments</span>
                            <span class="badge badge-green"><i class="fas fa-user-check"></i> {{ $det }} détenteurs</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="footer-info">
            <i class="fas fa-sync-alt"></i> Données calculées en temps réel • Dernière mise à jour : {{ now()->format('d M Y, H:i') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const domainLabels = @json($domaines);
const elementsParDomaine = @json($elementsParDomaine);
const detenteursParDomaine = @json($detenteursParDomaine);

document.addEventListener('DOMContentLoaded', function () {
    Chart.defaults.color = '#cbd5f5';
    Chart.defaults.borderColor = 'rgba(255,255,255,0.08)';

    new Chart(document.getElementById('chartDemandes'), {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'En cours', 'Validées', 'Rejetées'],
            datasets: [{
                data: [
                    {{ (int)($kpiDemandes['en_attente'] ?? 0) }},
                    {{ (int)($kpiDemandes['en_cours'] ?? 0) }},
                    {{ (int)($kpiDemandes['validees'] ?? 0) }},
                    {{ (int)($kpiDemandes['rejetees'] ?? 0) }}
                ],
                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 12
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 18 } }
            }
        }
    });

    new Chart(document.getElementById('chartDomaines'), {
        type: 'bar',
        data: {
            labels: domainLabels,
            datasets: [
                { label: 'Éléments', data: domainLabels.map(d => parseInt(elementsParDomaine[d] ?? 0)), backgroundColor: '#6366f1', borderRadius: 10 },
                { label: 'Détenteurs', data: domainLabels.map(d => parseInt(detenteursParDomaine[d] ?? 0)), backgroundColor: '#10b981', borderRadius: 10 }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.08)' } },
                x: { grid: { display: false } }
            },
            plugins: { legend: { position: 'top', labels: { padding: 15 } } }
        }
    });
});
</script>
@endpush
