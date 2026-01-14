@extends('layouts.app')

@section('content')

<h1>Admin Dashboard</h1>

<p>Bienvenue {{ auth()->user()->name }}</p>

<div class="alert">
    Vous êtes connecté en tant qu'Admin
</div>

<!-- Container dyal cards -->
<div class="cards" style="display:flex; gap:20px; flex-wrap:wrap;">

    <!-- Card 1: Utilisateurs (clickable) -->
    <a href="{{ route('admin.users') }}" style="text-decoration:none; color:inherit;">
        <div class="card" style="padding:20px; border:1px solid #ccc; border-radius:10px; width:200px; text-align:center; transition:0.3s; cursor:pointer;">
            <h3>Utilisateurs</h3>
            <p>Gestion des comptes utilisateurs</p>
        </div>
    </a>

    <!-- Card 2: Ressources -->
    <a href="#" style="text-decoration:none; color:inherit;">
        <div class="card" style="padding:20px; border:1px solid #ccc; border-radius:10px; width:200px; text-align:center; transition:0.3s; cursor:pointer;">
            <h3>Ressources</h3>
            <p>Gestion du parc informatique</p>
        </div>
    </a>

    <!-- Card 3: Statistiques -->
    <a href="#" style="text-decoration:none; color:inherit;">
        <div class="card" style="padding:20px; border:1px solid #ccc; border-radius:10px; width:200px; text-align:center; transition:0.3s; cursor:pointer;">
            <h3>Statistiques</h3>
            <p>Vue globale du Data Center</p>
        </div>
    </a>

</div>

<!-- Petit CSS pour hover effect -->
<style>
.cards .card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transform: translateY(-5px);
}
</style>

@endsection
