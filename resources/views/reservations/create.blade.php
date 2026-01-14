@extends('layouts.app')

@section('content')
<h2>Nouvelle réservation</h2>

<form method="POST" action="#">
    @csrf

    <label>Ressource</label><br>
    <input type="text" name="resource" placeholder="Serveur / VM"><br><br>

    <label>Date début</label><br>
    <input type="date" name="date_debut"><br><br>

    <label>Date fin</label><br>
    <input type="date" name="date_fin"><br><br>

    <label>Justification</label><br>
    <textarea name="justification"></textarea><br><br>

    <button type="submit">Envoyer</button>
</form>
@endsection
