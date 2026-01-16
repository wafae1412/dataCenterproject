@extends('layouts.app')

@section('content')
<div class="reservation-form-container">
    <div class="page-header">
        <h1>Nouvelle Réservation</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reservations.store') }}" class="form">
        @csrf

        <div class="form-group">
            <label for="resource_id">Ressource <span class="required">*</span></label>
            <select id="resource_id" name="resource_id" required class="form-control">
                <option value="">-- Sélectionnez une ressource --</option>
                @foreach($resources as $resource)
                    <optgroup label="{{ $resource->category->name }}">
                        <option value="{{ $resource->id }}" @if(old('resource_id') == $resource->id) selected @endif>
                            {{ $resource->name }} ({{ $resource->cpu }} CPU, {{ $resource->ram }}GB RAM)
                        </option>
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="date_start">Date/Heure Début <span class="required">*</span></label>
                <input type="datetime-local" id="date_start" name="date_start" required class="form-control" value="{{ old('date_start') }}">
                <small>Format: jj/mm/aaaa hh:mm</small>
            </div>

            <div class="form-group">
                <label for="date_end">Date/Heure Fin <span class="required">*</span></label>
                <input type="datetime-local" id="date_end" name="date_end" required class="form-control" value="{{ old('date_end') }}">
                <small>Doit être après la date de début</small>
            </div>
        </div>

        <div class="form-group">
            <label for="justification">Justification <span class="required">*</span></label>
            <textarea id="justification" name="justification" required class="form-control" 
                      placeholder="Expliquez l'objectif de cette réservation..." 
                      rows="5" minlength="10" maxlength="500">{{ old('justification') }}</textarea>
            <small>Entre 10 et 500 caractères</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Créer la Réservation</button>
            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>

    <div class="info-box">
        <h3>⚠️ Règles de réservation</h3>
        <ul>
            <li>La ressource doit être disponible pour la période demandée</li>
            <li>Une réservation doit être approuvée par un administrateur</li>
            <li>La justification doit être claire et complète</li>
        </ul>
    </div>
</div>
@endsection
