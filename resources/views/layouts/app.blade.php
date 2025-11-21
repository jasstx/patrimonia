<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patrimonia - @yield('title', 'Répertoire des Détenteurs de Patrimoine')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-green: #00AA00; /* vert vif */
            --brand-green-600: #008800;
            --brand-red: #FF0000;   /* rouge vif */
            --brand-red-600: #CC0000;

            --bg-body: #ffffff;
            --text-body: #1f2937;
            --card-bg: #ffffff;

            --sidebar-bg: #263445;
            --sidebar-link: #cbd5e1;
            --sidebar-hover: #1f2a37;
            --sidebar-active: var(--brand-green);
            --navbar-bg: #111827;
        }
        [data-theme="dark"] {
            --bg-body: #0b1220;
            --text-body: #e5e7eb;
            --card-bg: #0f172a;
            --sidebar-bg: #0f172a;
            --sidebar-link: #94a3b8;
            --sidebar-hover: #111827;
            --sidebar-active: var(--brand-green);
            --navbar-bg: #0b1220;
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: .2px;
            color: #e5e7eb !important;
            display: inline-flex; align-items:center; gap:.5rem;
        }
        .logo-img {
            height: 50px;
            width: auto;
            background: white;
            padding: 4px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }

        /* Header moderne */
        .navbar-modern {
            position: sticky; top: 0; z-index: 1020;
            background: linear-gradient(135deg, rgba(255,0,0,0.95), rgba(0,128,0,0.95));
            border-bottom: 1px solid rgba(255,255,255,.08);
            backdrop-filter: saturate(180%) blur(10px);
            box-shadow: 0 6px 24px rgba(0,0,0,.15);
        }
        .navbar-modern .navbar-brand i { filter: drop-shadow(0 2px 6px rgba(0,0,0,.25)); }
        .navbar-modern .navbar-text, .navbar-modern .nav-link { color:#e5e7eb !important; }
        .navbar-modern .navbar-text i { color:#fff; opacity:.9; }
        .navbar-modern .container { padding-top:.35rem; padding-bottom:.35rem; }
        .navbar-modern .divider { width:1px; height:28px; background:rgba(255,255,255,.15); margin:0 .75rem; }

        /* Bouton fantôme clair */
        .btn-ghost-light { color:#f3f4f6; border:1px solid rgba(255,255,255,.25); background:rgba(255,255,255,.06); }
        .btn-ghost-light:hover { color:#fff; border-color:rgba(255,255,255,.4); background:rgba(255,255,255,.12); }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: var(--sidebar-bg);
        }
        .sidebar .nav-link {
            color: var(--sidebar-link);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin: 0.125rem 0.5rem;
        }
        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #ffffff;
        }
        .sidebar .nav-link.active {
            background-color: var(--sidebar-active);
            color: #0b1220;
            font-weight: 600;
        }
        html, body { background: var(--bg-body); color: var(--text-body); }

        /* Layout par défaut piloté par le grid Bootstrap */

        /* Cartes sobres */
        .card, .shadow-xl { background: var(--card-bg); }

        /* Palette uniforme sans conflit (Bootstrap) */
        .btn-primary { 
            background: linear-gradient(135deg, var(--brand-red), var(--brand-green));
            border: none;
            color: white;
        }
        .btn-primary:hover { 
            background: linear-gradient(135deg, var(--brand-red-600), var(--brand-green-600));
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-outline-primary { 
            color: var(--brand-green); 
            border-color: var(--brand-green); 
            background: transparent;
        }
        .btn-outline-primary:hover { 
            background: linear-gradient(135deg, var(--brand-red), var(--brand-green));
            border-color: transparent;
            color: white;
        }

        .btn-danger { background-color: var(--brand-red); border-color: var(--brand-red); }
        .btn-danger:hover { background-color: var(--brand-red-600); border-color: var(--brand-red-600); }
        .btn-outline-danger { color: var(--brand-red); border-color: var(--brand-red); }
        .btn-outline-danger:hover { background-color: var(--brand-red); color: #fff; }

        .text-primary { color: var(--brand-green) !important; }
        .link-primary, a { color: var(--brand-green); }
        a:hover { color: var(--brand-green-600); }

        .badge-success, .bg-success { background-color: var(--brand-green) !important; }
        .badge-danger, .bg-danger { background-color: var(--brand-red) !important; }

        /* Footer moderne */
        .footer-modern {
            background: linear-gradient(135deg, rgba(255,0,0,0.95), rgba(0,128,0,0.95));
            border-top: 1px solid rgba(255,255,255,.08);
            margin-top: 4rem;
            color: #e5e7eb;
        }
        .footer-logo {
            height: 60px;
            width: auto;
            background: white;
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        .footer-text {
            font-size: 1rem;
            color: #f3f4f6;
            margin: 0;
        }
        .footer-copyright {
            font-size: 0.875rem;
            color: #cbd5e1;
        }
        .footer-links {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        @media (min-width: 768px) {
            .footer-links {
                justify-content: flex-end;
            }
        }
        .footer-link {
            color: #e5e7eb;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .footer-link:hover {
            color: var(--brand-green);
        }

        /* Aucune règle spéciale: le grid gère l'alignement */
    </style>
    @stack('styles')
    @php
        // Calculer ici pour pouvoir l'utiliser dans l'attribut de <body>
        $hideRoutes = ['home', 'detenteurs.index', 'patrimoines.index', 'demande.index', 'demande.show'];
        $showSidebar = auth()->check() && !request()->routeIs($hideRoutes);
    @endphp
</head>
<body class="{{ $showSidebar ? 'with-sidebar' : '' }}">
    @php
        // Déterminer si la sidebar doit être affichée (utilisé dès la navbar)
        $hideRoutes = ['home', 'detenteurs.index', 'patrimoines.index', 'demande.index', 'demande.show'];
        $showSidebar = auth()->check() && !request()->routeIs($hideRoutes);
    @endphp
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-modern">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Patrimonia Logo" class="logo-img"> Patrimonia
            </a>

            <div class="navbar-nav ms-auto" style="align-items:center; gap:.5rem;">
                @if($showSidebar)
                <button id="sidebarToggle" class="btn btn-ghost-light btn-sm me-2 d-md-none" type="button" title="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                @endif
                <div class="d-none d-md-block divider"></div>
                <button id="themeToggle" class="btn btn-ghost-light btn-sm me-2" type="button" title="Basculer thème">
                    <i class="fas fa-moon"></i>
                </button>
                @auth
                    <span class="navbar-text me-2">
                        <i class="fas fa-user"></i> {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost-light btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost-light btn-sm">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container-fluid">
        <div class="row">
            @php
                // (déjà défini plus haut) conservé pour contexte local
            @endphp
            @if($showSidebar)
            @include('partials.sidebar')
            @endif

            <!-- Main content -->
            <main class="@if(!$showSidebar) col-12 @else col-md-9 ms-sm-auto col-lg-10 @endif px-md-4">
                <div class="pt-3">
                    <!-- Messages flash -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-modern">
        <div class="container-fluid">
            <div class="row align-items-center py-4">
                <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                    <img src="{{ asset('images/mccat.jpeg') }}" alt="MCCAT" class="footer-logo">
                </div>
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <p class="footer-text mb-2">
                        <strong>Patrimonia</strong> - Répertoire des Détenteurs de Patrimoine
                    </p>
                    <p class="footer-copyright mb-0">
                        &copy; {{ date('Y') }} Ministère de la Culture, des Arts et du Tourisme
                    </p>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <div class="footer-links">
                        <a href="#" class="footer-link"><i class="fas fa-info-circle"></i> À propos</a>
                        <a href="#" class="footer-link"><i class="fas fa-phone"></i> Contact</a>
                        <a href="#" class="footer-link"><i class="fas fa-shield-alt"></i> Confidentialité</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Scripts simples
        document.addEventListener('DOMContentLoaded', function() {
            const preferred = localStorage.getItem('theme') || 'light';
            if (preferred === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
            
            // Theme toggle
            const themeToggle = document.getElementById('themeToggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                    const next = current === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next === 'dark' ? 'dark' : '');
                    localStorage.setItem('theme', next);
                    this.innerHTML = next === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
                });
                // Set initial icon
                themeToggle.innerHTML = (preferred === 'dark') ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            }

            // Sidebar toggle for mobile
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
                
                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768 && 
                        !sidebar.contains(e.target) && 
                        !sidebarToggle.contains(e.target) && 
                        sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                });
            }
            
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    @stack('scripts')
</body>
</html>
