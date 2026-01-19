@extends('layouts.app') {{-- Layout principal --}}

@section('title', 'Modifier la Ressource') {{-- Titre de la page --}}

@section('styles')
<link rel="stylesheet" href="{{ asset('css/resources/edit.css') }}"> {{-- CSS spécifique --}}
@endsection

@section('content')
<div class="resources-edit">
    {{-- En-tête de la page --}}
    <div class="page-header">
        <div class="header-info">
            <h1>Modifier: {{ $resource->name }}</h1>
            <div class="resource-meta">
                {{-- ID de la ressource --}}
                <span class="badge">ID: {{ $resource->id }}</span>

                {{-- Statut avec classe CSS --}}
                <span class="status-badge {{ $resource->status }}">{{ $resource->status }}</span>

                {{-- Date de création avec vérification null --}}
                <span class="created-date">Créée le: {{ $resource->created_at?->format('d/m/Y') ?? 'Date inconnue' }}</span>
            </div>
        </div>

        {{-- Boutons d'action --}}
        <div class="header-actions">
            {{-- Voir la ressource --}}
            <a href="{{ route('resources.show', $resource) }}" class="btn view">Voir</a>

            {{-- Retour à la liste --}}
            <a href="{{ route('resources.index') }}" class="btn secondary">Retour</a>
        </div>
    </div>

    {{-- Conteneur du formulaire --}}
    <div class="form-container">
        {{-- Formulaire de modification --}}
        <form action="{{ route('resources.update', $resource) }}" method="POST" id="resource-edit-form">
            @csrf @method('PUT') {{-- Protection CSRF et méthode PUT --}}

            {{-- Grille des champs --}}
            <div class="form-grid">
                {{-- Nom de la ressource --}}
                <div class="form-group">
                    <label for="name" class="required">Nom</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $resource->name) }}"
                           required>
                </div>

                {{-- Catégorie --}}
                <div class="form-group">
                    <label for="category_id" class="required">Catégorie</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                {{ (old('category_id', $resource->category_id) == $category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Statut --}}
                <div class="form-group">
                    <label for="status" class="required">Statut</label>
                    <select id="status" name="status" required>
                        <option value="available" {{ old('status', $resource->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="reserved" {{ old('status', $resource->status) == 'reserved' ? 'selected' : '' }}>Réservé</option>
                        <option value="maintenance" {{ old('status', $resource->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="disabled" {{ old('status', $resource->status) == 'disabled' ? 'selected' : '' }}>Désactivé</option>
                    </select>
                </div>

                {{-- CPU --}}
                <div class="form-group">
                    <label for="cpu" class="required">CPU (cores)</label>
                    <input type="number" id="cpu" name="cpu"
                           value="{{ old('cpu', $resource->cpu) }}"
                           required min="1" max="128">
                </div>

                {{-- RAM --}}
                <div class="form-group">
                    <label for="ram" class="required">RAM (GB)</label>
                    <input type="number" id="ram" name="ram"
                           value="{{ old('ram', $resource->ram) }}"
                           required min="1" max="1024">
                </div>

                {{-- Stockage --}}
                <div class="form-group">
                    <label for="storage" class="required">Stockage (GB)</label>
                    <input type="number" id="storage" name="storage"
                           value="{{ old('storage', $resource->storage) }}"
                           required min="1" max="100000">
                </div>

                {{-- Localisation --}}
                <div class="form-group">
                    <label for="location">Localisation</label>
                    <input type="text" id="location" name="location"
                           value="{{ old('location', $resource->location) }}"
                           placeholder="Ex: Rack A-01">
                </div>
            </div>

            {{-- Description --}}
            <div class="form-group full-width">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4">{{ old('description', $resource->description) }}</textarea>
            </div>

            {{-- Informations des réservations --}}
            <div class="reservations-info">
                <h3>Informations de réservation</h3>
                <div class="reservations-stats">
                    {{-- Réservations actives --}}
                    <div class="stat">
                        <span class="stat-label">Réservations actives:</span>
                        <span class="stat-value">{{ $resource->reservations->where('status', 'active')->count() }}</span>
                    </div>

                    {{-- Réservations totales --}}
                    <div class="stat">
                        <span class="stat-label">Réservations totales:</span>
                        <span class="stat-value">{{ $resource->reservations->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions du formulaire --}}
            <div class="form-actions">
                {{-- Bouton de mise à jour --}}
                <button type="submit" class="btn primary">Mettre à jour</button>

                {{-- Bouton de réinitialisation --}}
                <button type="button" class="btn reset" onclick="this.form.reset()">Réinitialiser</button>

                {{-- Bouton d'annulation --}}
                <a href="{{ route('resources.index') }}" class="btn cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/resources/edit.js') }}"></script> {{-- JavaScript spécifique --}}
@endsection
