<nav class="col-md-3 col-lg-2 d-none d-md-block sidebar" style="background-color: white; border-right: 1px solid #e5e7eb;">
    <div class="position-sticky pt-3">
        <style>
            .sidebar {
                min-height: 100vh;
                box-shadow: 2px 0 8px rgba(0,0,0,0.05);
            }
            .nav-link {
                color: #4b5563 !important;
                padding: 0.75rem 1.5rem;
                margin: 0.25rem 0.75rem;
                border-radius: 0.5rem;
                transition: all 0.2s;
            }
            .nav-link:hover {
                background-color: #f3f4f6;
                color: #1f2937 !important;
            }
            .nav-link.active {
                background: linear-gradient(135deg, #FF0000, #00AA00);
                color: white !important;
                font-weight: 500;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .nav-link i {
                width: 20px;
                text-align: center;
                margin-right: 0.5rem;
            }
            .sidebar-header {
                padding: 1rem 1.5rem;
                margin-bottom: 1rem;
                border-bottom: 1px solid #e5e7eb;
            }
        </style>
        <div class="sidebar-header">
            <h5 class="mb-0">TABLEAU DE BORD</h5>
        </div>
        <ul class="nav flex-column">
            @guest
            <li class="nav-item text-uppercase text-gray-500 text-xs font-medium px-4 mb-2">Navigation</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="fas fa-house me-2"></i> Accueil
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('detenteurs.index') ? 'active' : '' }}" href="{{ route('detenteurs.index') }}">
                    <i class="fas fa-users me-2"></i> Détenteurs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('patrimoines.index') ? 'active' : '' }}" href="{{ route('patrimoines.index') }}">
                    <i class="fas fa-landmark me-2"></i> Patrimoines
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-2"></i> Connexion
                </a>
            </li>
            @endguest
            @php $user = auth()->user(); @endphp
            @if($user && method_exists($user, 'isAdministrateur') && $user->isAdministrateur())
            <li class="nav-item text-uppercase text-white-50 small px-3 mb-2">Administration</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-chart-line me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.utilisateurs*') ? 'active' : '' }}" href="{{ route('admin.utilisateurs') }}">
                    <i class="fas fa-users me-2"></i> Utilisateurs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}" href="{{ route('admin.roles') }}">
                    <i class="fas fa-user-shield me-2"></i> Rôles
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.statistiques') ? 'active' : '' }}" href="{{ route('admin.statistiques') }}">
                    <i class="fas fa-chart-pie me-2"></i> Statistiques
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.permissions*') ? 'active' : '' }}" href="{{ route('admin.permissions') }}">
                    <i class="fas fa-key me-2"></i> Permissions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}" href="{{ route('admin.categories') }}">
                    <i class="fas fa-tags me-2"></i> Catégories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.patrimoines*') ? 'active' : '' }}" href="{{ route('admin.patrimoines') }}">
                    <i class="fas fa-landmark me-2"></i> Patrimoines
                </a>
            </li>
            @endif

            @if($user && method_exists($user, 'isGestionnaire') && ($user->isGestionnaire() || ($user->isAdministrateur() ?? false)))
            <li class="nav-item text-uppercase text-white-50 small px-3 mt-3 mb-2">Gestion</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('gestionnaire.dashboard') ? 'active' : '' }}" href="{{ route('gestionnaire.dashboard') }}">
                    <i class="fas fa-tasks me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('gestionnaire.demandes*') ? 'active' : '' }}" href="{{ route('gestionnaire.demandes') }}">
                    <i class="fas fa-file-alt me-2"></i> Demandes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('gestionnaire.detenteurs.valides*') ? 'active' : '' }}" href="{{ route('gestionnaire.detenteurs.valides') }}">
                    <i class="fas fa-check-circle me-2"></i> Détenteurs validés
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('gestionnaire.detenteurs.refuses*') ? 'active' : '' }}" href="{{ route('gestionnaire.detenteurs.refuses') }}">
                    <i class="fas fa-times-circle me-2"></i> Détenteurs refusés
                </a>
            </li>
            @endif

            @auth
            @php
                $isAdmin = $user && method_exists($user, 'isAdministrateur') && $user->isAdministrateur();
                $isGest = $user && method_exists($user, 'isGestionnaire') && $user->isGestionnaire();
            @endphp
            @if(!$isAdmin && !$isGest)
            <li class="nav-item text-uppercase text-white-50 small px-3 mt-3 mb-2">Mon espace</li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('demande.create') ? 'active' : '' }}" href="{{ route('demande.create') }}">
                    <i class="fas fa-plus-circle me-2"></i> Nouvelle demande
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profil.index') ? 'active' : '' }}" href="{{ route('profil.index') }}">
                    <i class="fas fa-user-circle me-2"></i> Mon profil
                </a>
            </li>
            @endif
            @endauth
        </ul>
    </div>
</nav>
