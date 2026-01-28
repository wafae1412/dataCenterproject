@extends('layouts.app')

@section('title', 'Modifier Maintenance')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/maintenance/edit.css') }}">
@endsection

@section('content')
<div class="maintenance-edit-container">
    <div class="page-header">
        <h1>Modifier la Maintenance #{{ $maintenance->id }}</h1>
        <a href="{{ route('maintenances.show', $maintenance) }}" class="btn btn-secondary">Annuler</a>
    </div>

    <div class="form-card">
        <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <!-- Colonne Gauche -->
                <div class="form-column">
                    <div class="form-group">
                        <label>Ressource concernée</label>
                        <input type="text" class="form-control" value="{{ $maintenance->resource->name }}" disabled>
                        <small class="text-muted">La ressource ne peut pas être modifiée une fois la maintenance créée.</small>
                    </div>

                    <div class="form-group">
                        <label for="title">Titre de l'intervention *</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $maintenance->title) }}" required>
                        @error('title') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="type">Type de maintenance *</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="preventive" {{ $maintenance->type == 'preventive' ? 'selected' : '' }}>Préventive</option>
                            <option value="corrective" {{ $maintenance->type == 'corrective' ? 'selected' : '' }}>Corrective</option>
                            <option value="emergency" {{ $maintenance->type == 'emergency' ? 'selected' : '' }}>Urgence</option>
                            <option value="upgrade" {{ $maintenance->type == 'upgrade' ? 'selected' : '' }}>Mise à niveau</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Statut actuel *</label>
                        <select name="status" id="status" class="form-control status-select" required>
                            <option value="scheduled" {{ $maintenance->status == 'scheduled' ? 'selected' : '' }}>Planifiée</option>
                            <option value="in_progress" {{ $maintenance->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="completed" {{ $maintenance->status == 'completed' ? 'selected' : '' }}>Terminée</option>
                            <option value="cancelled" {{ $maintenance->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Changer le statut à "Terminée" ou "Annulée" rendra la ressource disponible.</small>
                    </div>
                </div>

                <!-- Colonne Droite -->
                <div class="form-column">
                    <div class="form-row">
                        <div class="form-group half">
                            <label for="start_date">Date de début *</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control" 
                                   value="{{ old('start_date', $maintenance->start_date->format('Y-m-d\TH:i')) }}" required>
                        </div>
                        <div class="form-group half">
                            <label for="end_date">Date de fin *</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control" 
                                   value="{{ old('end_date', $maintenance->end_date->format('Y-m-d\TH:i')) }}" required>
                        </div>
                    </div>
                    @error('start_date') <span class="error block">{{ $message }}</span> @enderror
                    @error('end_date') <span class="error block">{{ $message }}</span> @enderror

                    <div class="form-group">
                        <label for="estimated_duration">Durée estimée (heures)</label>
                        <input type="number" name="estimated_duration" id="estimated_duration" class="form-control" 
                               value="{{ old('estimated_duration', $maintenance->estimated_duration) }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Description détaillée</label>
                        <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $maintenance->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes internes</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control notes-input">{{ old('notes', $maintenance->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('js/maintenance/edit.js') }}"></script>
@endsection