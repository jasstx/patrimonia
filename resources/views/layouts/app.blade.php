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
        .navbar-brand {
            font-weight: bold;
            color: #2c3e50 !important;
        }
        .bg-primary {
            background-color: #2c3e50 !important;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #34495e;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link:hover {
            background-color: #2c3e50;
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #3498db;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-landmark"></i> Patrimonia
            </a>

            <div class="navbar-nav ms-auto">
                @auth
                    <span class="navbar-text me-3">
                        <i class="fas fa-user"></i> {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container-fluid">
        <div class="row">
            @if(auth()->check() && (auth()->user()->isAdministrateur() || auth()->user()->isGestionnaire()))
            <!-- Sidebar pour admin/gestionnaire -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        @if(auth()->user()->isAdministrateur())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('admin/utilisateurs*') ? 'active' : '' }}" href="{{ route('admin.utilisateurs') }}">
                                <i class="fas fa-users"></i> Utilisateurs
                            </a>
                        </li>
                        @endif

                        @if(auth()->user()->isGestionnaire() || auth()->user()->isAdministrateur())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('gestionnaire*') ? 'active' : '' }}" href="{{ route('gestionnaire.dashboard') }}">
                                <i class="fas fa-tasks"></i> Dashboard Gestionnaire
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('gestionnaire/demandes*') ? 'active' : '' }}" href="{{ route('gestionnaire.demandes') }}">
                                <i class="fas fa-file-alt"></i> Demandes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('gestionnaire/detenteurs*') ? 'active' : '' }}" href="{{ route('gestionnaire.detenteurs') }}">
                                <i class="fas fa-user-check"></i> Détenteurs
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </nav>
            @endif

            <!-- Main content -->
            <main class="@if(auth()->check() && (auth()->user()->isAdministrateur() || auth()->user()->isGestionnaire())) col-md-9 ms-sm-auto col-lg-10 @else col-12 @endif px-md-4">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Scripts simples
        document.addEventListener('DOMContentLoaded', function() {
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
</body>
</html>
