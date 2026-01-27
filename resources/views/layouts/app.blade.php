 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCenter Project</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="#">DataCenter</a>

        @auth
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('notifications.index') }}">Notifications</a>
            <a href="{{ route('maintenances.index') }}">Maintenances</a>
            <a href="{{ route('resources.index') }}">Ressources</a>

            @if(auth()->user()->role->name === 'Admin')
                <a href="/admin/dashboard">Admin Dashboard</a>
            @endif

            @if(auth()->user()->role->name === 'Responsable')
                <a href="/responsable/dashboard">Responsable Dashboard</a>
            @endif
        @endauth
    </div>

    <div class="nav-right">
        <button id="darkModeBtn" onclick="toggleDark()">Dark Mode</button>

        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button id="logoutBtn" type="submit">Logout</button>
        </form>
        @endauth
    </div>
</nav>

@if(Route::current()->getName() === 'login' || Route::current()->getName() === 'register')
    @yield('content')
@else
    <div style="max-width: 1200px; margin: 2rem auto; padding: 2rem; background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        @yield('content')
    </div>
@endif

<script>
function toggleDark() {
    document.body.classList.toggle('dark');
}
</script>

@yield('scripts')

</body>
</html>
