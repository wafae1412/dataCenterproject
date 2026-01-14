 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Responsable Dashboard</title>
</head>
<body>

     
    @extends('layouts.app')

@section('content')
<h1>Responsable Dashboard</h1>

<p>Bienvenue {{ auth()->user()->name }}</p>

<div class="alert alert-info">
    Vous êtes connecté en tant que Responsable
</div>


<div class="cards">
    <div class="card">
        <h3>Ressources</h3>
        <p>Gestion des ressources disponibles</p>
    </div>

    <div class="card">
        <h3>Réservations</h3>
        <p>Validation / Refus des demandes</p>
    </div>
</div>
@endsection


    
</body>
</html>
