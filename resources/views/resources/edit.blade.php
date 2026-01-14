@extends('layouts.app')

@section('title', 'Modifier la Ressource')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/resources/edit.css') }}">
@endsection

@section('content')
<div class="resources-edit">
    <div class="page-header">
        <div class="header-info">
            <h1>Modifier: {{ $resource->name }}</h1>
            <div class="resource-meta">
                <span class="badge">ID: {{ $resource->id }}</span>
                <span class="status-badge {{ $resource->status }}">{{ $resource->status }}</span>
                <span class="created-date">Créée le: {{ $resource->created_at->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="header-actions">
            <a href="{{ route('resources.show', $resource) }}" class="btn view">Voir</a>
            <a href="{{ route('resources.index') }}" class="btn secondary">Retour</a>
        </div>
    </div>

    <div class="form-container">
        <form action="{{ route('resources.update', $resource) }}" method="POST" id="resource-edit-form">
            @csrf @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="name" class="required">Nom</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name', $resource->name) }}"
                           required>
                </div>

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

                <div class="form-group">
                    <label for="status" class="required">Statut</label>
                    <select id="status" name="status" required>
                        <option value="available" {{ old('status', $resource->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="reserved" {{ old('status', $resource->status) == 'reserved' ? 'selected' : '' }}>Réservé</option>
                        <option value="maintenance" {{ old('status', $resource->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="disabled" {{ old('status', $resource->status) == 'disabled' ? 'selected' : '' }}>Désactivé</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cpu" class="required">CPU (cores)</label>
                    <input type="number" id="cpu" name="cpu"
                           value="{{ old('cpu', $resource->cpu) }}"
                           required min="1" max="128">
                </div>

                <div class="form-group">
                    <label for="ram" class="required">RAM (GB)</label>
                    <input type="number" id="ram" name="ram"
                           value="{{ old('ram', $resource->ram) }}"
                           required min="1" max="1024">
                </div>

                <div class="form-group">
                    <label for="storage" class="required">Stockage (GB)</label>
                    <input type="number" id="storage" name="storage"
                           value="{{ old('storage', $resource->storage) }}"
                           required min="1" max="100000">
                </div>

                <div class="form-group">
                    <label for="location">Localisation</label>
                    <input type="text" id="location" name="location"
                           value="{{ old('location', $resource->location) }}"
                           placeholder="Ex: Rack A-01">
                </div>
            </div>

            <div class="form-group full-width">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4">{{ old('description', $resource->description) }}</textarea>
            </div>

            <div class="reservations-info">
                <h3>Informations de réservation</h3>
                <div class="reservations-stats">
                    <div class="stat">
                        <span class="stat-label">Réservations actives:</span>
                        <span class="stat-value">{{ $resource->reservations->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="stat">
                        <span class="stat-label">Réservations totales:</span>
                        <span class="stat-value">{{ $resource->reservations->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary">Mettre à jour</button>
                <button type="button" class="btn reset" onclick="this.form.reset()">Réinitialiser</button>
                <a href="{{ route('resources.index') }}" class="btn cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/resources/edit.js') }}"></script>
@endsection
