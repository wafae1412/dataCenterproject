@extends('layouts.app')

@section('title', 'Planifier Maintenance')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/maintenance/create.css') }}">
@endsection

@section('content')
<div class="maintenance-create-container">
    {{-- En-tête --}}
    <div class="page-header">
        <h1 class="page-title">Planifier une Maintenance</h1>
        <a href="{{ url()->previous() }}" class="back-button">
            Retour
        </a>
    </div>

    {{-- Formulaire --}}
    <div class="maintenance-form-card">
        <form action="{{ route('maintenances.store') }}" method="POST" class="maintenance-form" id="maintenanceForm">
            @csrf

            {{-- SECTION 1: Ressource --}}
            <div class="form-section">
                <h3 class="section-title">1. Ressource à maintenir</h3>

                <div class="form-group">
                    <label for="resource_id" class="form-label">Ressource *</label>
                    <select name="resource_id" id="resource_id" class="form-select" required>
                        <option value="">-- Sélectionner une ressource --</option>
                        @foreach($resources as $resource)
                        <option value="{{ $resource->id }}"
                                {{ old('resource_id') == $resource->id ? 'selected' : '' }}>
                            {{ $resource->name }} ({{ $resource->type }})
                            - Statut: {{ $resource->status }}
                        </option>
                        @endforeach
                    </select>
                    @error('resource_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- SECTION 2: Détails --}}
            <div class="form-section">
                <h3 class="section-title">2. Détails de la maintenance</h3>

                <div class="form-group">
                    <label for="title" class="form-label">Titre *</label>
                    <input type="text" name="title" id="title" class="form-input"
                           value="{{ old('title') }}"
                           placeholder="Ex: Mise à jour firmware" required>
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" rows="3" class="form-textarea"
                              placeholder="Détails de la maintenance...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type" class="form-label">Type de maintenance *</label>
                    <select name="type" id="type" class="form-select" required>
                    <option value="">-- Choisir un type --</option>
                    <option value="preventive" {{ old('type') == 'preventive' ? 'selected' : '' }}>Préventive</option>
                    <option value="corrective" {{ old('type') == 'corrective' ? 'selected' : '' }}>Corrective</option>
                    <option value="urgence" {{ old('type') == 'urgence' ? 'selected' : '' }}>Urgence</option>
                    <option value="mise_a_niveau" {{ old('type') == 'mise_a_niveau' ? 'selected' : '' }}>Mise à niveau</option>
                </select>
            @error('type')
           <div class="error-message">{{ $message }}</div>
          @enderror
         </div>
      

            {{-- SECTION 3: Dates --}}
            <div class="form-section">
                <h3 class="section-title">3. Planification</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date" class="form-label">Date de début *</label>
                        <input type="datetime-local" name="start_date" id="start_date"
                               class="form-input" value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="form-label">Date de fin *</label>
                        <input type="datetime-local" name="end_date" id="end_date"
                               class="form-input" value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="estimated_duration" class="form-label">Durée estimée (heures)</label>
                    <input type="number" name="estimated_duration" id="estimated_duration"
                           class="form-input" value="{{ old('estimated_duration') }}" min="1" max="720">
                    @error('estimated_duration')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- SECTION 4: Notes --}}
            <div class="form-section">
                <h3 class="section-title">4. Notes supplémentaires</h3>

                <div class="form-group">
                    <label for="notes" class="form-label">Notes internes</label>
                    <textarea name="notes" id="notes" rows="2" class="form-textarea"
                              placeholder="Informations pour les techniciens...">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="form-actions">
                <button type="submit" class="submit-button">Planifier la maintenance</button>
                <a href="{{ route('resources.index') }}" class="cancel-button">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/maintenance/create.js') }}"></script>
@endsection
