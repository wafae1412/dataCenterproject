<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DataCenter Hub')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Global Styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
    
    @stack('styles')
    @yield('styles')
</head>
<body>

<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <a href="/" class="brand-link">
                <i class="fas fa-server"></i>
                <span>DataCenter Hub</span>
            </a>
        </div>

        <button class="navbar-toggle" id="navbarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <div class="navbar-menu" id="navbarMenu">
             
            <div class="nav-left">
    @auth
        @if(auth()->user()->role->name === 'Admin')
            {{-- Admin Links --}}
           
            <a href="/dashboard" class="nav-link {{ request()->is('dashboard') || request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Utilisateurs
            </a>
            <a href="{{ route('maintenances.index') }}" class="nav-link {{ request()->is('maintenances*') ? 'active' : '' }}">
                <i class="fas fa-tools"></i> Maintenances
            </a>
        @elseif(auth()->user()->role->name === 'Responsable')
            {{-- Responsable Links --}}
            <a href="/dashboard" class="nav-link {{ request()->is('dashboard') || request()->is('responsable/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('resources.index') }}" class="nav-link {{ request()->is('resources*') ? 'active' : '' }}">
                <i class="fas fa-database"></i> Ressources
            </a>
            <a href="{{ route('maintenances.index') }}" class="nav-link {{ request()->is('maintenances*') ? 'active' : '' }}">
                <i class="fas fa-tools"></i> Maintenances
            </a>
        @elseif(auth()->user()->role->name === 'Guest')
            {{-- Guest Links --}}
            <a href="{{ route('guest.dashboard') }}" class="nav-link {{ request()->is('guest/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('resources.index') }}" class="nav-link {{ request()->is('resources*') ? 'active' : '' }}">
                <i class="fas fa-database"></i> Ressources
            </a>
        @else
            {{-- Internal user links --}}
            <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('resources.index') }}" class="nav-link {{ request()->is('resources*') ? 'active' : '' }}">
                <i class="fas fa-database"></i> Ressources
            </a>
            <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->is('reservations*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Réservations
            </a>
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Catégories
            </a>
        @endif
    @endauth
</div>


            <div class="nav-right">
                {{-- Theme Toggle --}}
                <button class="nav-link theme-toggle" id="darkModeBtn" title="Mode sombre">
                    <i class="fas fa-moon"></i>
                </button>

                @auth
                    {{-- Notifications --}}
                    <a href="{{ route('notifications.index') }}" class="nav-link notification-link" title="Notifications">
                        <i class="fas fa-bell"></i>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="notification-badge">{{ auth()->user()->unreadNotifications()->count() }}</span>
                        @endif
                    </a>

                    <div class="user-dropdown">
                        <button class="user-btn">
                            <div class="user-avatar">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="dropdown-header">
                                <strong>{{ Auth::user()->name }}</strong>
                                <span class="role-badge role-{{ strtolower(Auth::user()->role->name) }}">{{ Auth::user()->role->name }}</span>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                <i class="fas fa-user"></i> Mon Profil
                            </a>
                            <a href="{{ route('settings') }}" class="dropdown-item">
                                <i class="fas fa-cog"></i> Paramètres
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-small">Inscription</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<div class="main-container">
    @yield('content')
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navbar toggle for mobile
    const navbarToggle = document.getElementById('navbarToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    
    if (navbarToggle) {
        navbarToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('active');
        });
    }

    // Dark mode toggle
    const darkModeBtn = document.getElementById('darkModeBtn');
    const htmlElement = document.documentElement;
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        htmlElement.setAttribute('data-theme', 'dark');
        if (darkModeBtn) darkModeBtn.classList.add('active');
    }
    
    if (darkModeBtn) {
        darkModeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const currentTheme = htmlElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            darkModeBtn.classList.toggle('active');
        });
    }

    // User dropdown
    const userBtn = document.querySelector('.user-btn');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    if (userBtn) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (dropdownMenu) {
                dropdownMenu.classList.toggle('active');
            }
        });
        
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-dropdown') && dropdownMenu) {
                dropdownMenu.classList.remove('active');
            }
        });
    }
});
</script>

<style>
  
    
    /* Pour tous les liens de navigation */
    .nav-link {
        color: #ffffff !important;
    }
    
    .nav-link i {
        color: #ffffff !important;
    }
    
    /* Pour les liens actifs */
    .nav-link.active {
        color: #ffffff !important;
        background-color: rgba(255, 255, 255, 0.2) !important;
    }
    
    .nav-link.active i {
        color: #ffffff !important;
    }
    
    /* Pour le bouton utilisateur */
    .user-btn {
        color: #ffffff !important;
    }
    
    .user-name {
        color: #ffffff !important;
    }
    
    .user-btn i {
        color: #ffffff !important;
    }
    
    /* Pour les liens au survol */
    .nav-link:hover {
        color: #ffffff !important;
        background-color: rgba(255, 255, 255, 0.15) !important;
    }
    
    .nav-link:hover i {
        color: #ffffff !important;
    }
    
   
    .theme-toggle {
        color: #ffffff !important;
    }
    
    .theme-toggle i {
        color: #ffffff !important;
    }
    
   
    .notification-link {
        color: #ffffff !important;
    }
    
    .notification-link i {
        color: #ffffff !important;
    }
    
    
    .user-avatar {
        width: 32px;
        height: 32px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 0.5rem;
    }
    
    .dropdown-header {
        padding: 0.75rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        background: var(--background);
    }
    
    .notification-link {
        position: relative;
        margin-right: 1rem;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -8px;
        background-color: #ef4444;
        color: white;
        border-radius: 50%;
        padding: 0.15rem 0.4rem;
        font-size: 0.7rem;
        font-weight: bold;
        line-height: 1;
        min-width: 16px;
        text-align: center;
    }
</style>

</body>
</html>