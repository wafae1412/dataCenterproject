<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCenter Project</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
                    <a href="/dashboard" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/notifications" class="nav-link">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                    <a href="/maintenances" class="nav-link">
                        <i class="fas fa-tools"></i>
                        <span>Maintenances</span>
                    </a>

                    @if(auth()->user()->role->name === 'Admin')
                        <a href="/admin/dashboard" class="nav-link admin-link">
                            <i class="fas fa-shield-alt"></i>
                            <span>Admin</span>
                        </a>
                    @endif

                    @if(auth()->user()->role->name === 'Responsable')
                        <a href="/responsable/dashboard" class="nav-link responsable-link">
                            <i class="fas fa-user-tie"></i>
                            <span>Responsable</span>
                        </a>
                    @endif
                @endauth
            </div>

            <div class="nav-right">
                <button class="nav-link theme-toggle" id="darkModeBtn" title="Toggle dark mode">
                    <i class="fas fa-moon"></i>
                    <span>Dark Mode</span>
                </button>

                @auth
                    <div class="user-dropdown">
                        <button class="user-btn">
                            <i class="fas fa-user-circle"></i>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="/profile" class="dropdown-item">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <a href="/settings" class="dropdown-item">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
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

</body>
</html>
