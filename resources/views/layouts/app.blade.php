 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DataCenter Project</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="#">DataCenter</a>

        @auth
            <a href="/notifications">Notifications</a>
            <a href="/maintenances">Maintenances</a>

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

<div class="container">
    @yield('content')
</div>

<script>
function toggleDark() {
    document.body.classList.toggle('dark');
}
</script>

</body>
</html>
