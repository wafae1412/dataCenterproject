@extends('layouts.app')

@section('title', 'Créer une Ressource')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/resources/create.css') }}">
@endsection

@section('content')
<div class="resources-create">
    <div class="page-header">
        <h1>Créer une Nouvelle Ressource</h1>
        <a href="{{ route('resources.index') }}" class="btn secondary">Retour à la liste</a>
    </div>

    <div class="form-container">
        <form action="{{ route('resources.store') }}" method="POST" id="resource-form">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="name" class="required">Nom de la ressource</label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Ex: Serveur Web 01">
                    @error('name')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id" class="required">Catégorie</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cpu" class="required">CPU (cores)</label>
                    <input type="number" id="cpu" name="cpu"
                           value="{{ old('cpu') }}"
                           required min="1" max="128"
                           placeholder="Ex: 8">
                    @error('cpu')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="ram" class="required">RAM (GB)</label>
                    <input type="number" id="ram" name="ram"
                           value="{{ old('ram') }}"
                           required min="1" max="1024"
                           placeholder="Ex: 16">
                    @error('ram')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="storage" class="required">Stockage (GB)</label>
                    <input type="number" id="storage" name="storage"
                           value="{{ old('storage') }}"
                           required min="1" max="100000"
                           placeholder="Ex: 500">
                    @error('storage')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="location">Localisation</label>
                    <input type="text" id="location" name="location"
                           value="{{ old('location') }}"
                           placeholder="Ex: Rack A-01">
                    @error('location')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group full-width">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                          rows="4"
                          placeholder="Description de la ressource...">{{ old('description') }}</textarea>
                @error('description')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="specs-summary">
                <h3>Récapitulatif des spécifications</h3>
                <div class="specs-display">
                    <div class="spec-item">
                        <span class="spec-label">CPU:</span>
                        <span id="cpu-preview" class="spec-value">0 cores</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">RAM:</span>
                        <span id="ram-preview" class="spec-value">0 GB</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Stockage:</span>
                        <span id="storage-preview" class="spec-value">0 GB</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary">Créer la Ressource</button>
                <button type="reset" class="btn reset">Réinitialiser</button>
                <a href="{{ route('resources.index') }}" class="btn cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/resources/create.js') }}"></script>
@endsection
