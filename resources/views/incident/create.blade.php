@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 3rem auto; padding: 2rem;">
    <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 2rem;">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: #0a2a43; margin: 0 0 0.5rem 0; font-size: 1.75rem;">Signaler un Incident Technique</h1>
            <p style="color: #6b7280; margin: 0;">Informez-nous de tout problème ou incident que vous rencontrez</p>
        </div>

        @if(session('success'))
            <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; margin-bottom: 1.5rem; border-radius: 6px; border-left: 4px solid #10b981;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #fee2e2; color: #7f1d1d; padding: 1rem; margin-bottom: 1.5rem; border-radius: 6px; border-left: 4px solid #ef4444;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('incident.store') }}" style="display: grid; gap: 1.5rem;">
            @csrf

            {{-- Titre --}}
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0a2a43;">
                    Titre du Problème <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Ex: Serveur inaccessible, Erreur de connexion..." style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;" required>
                @error('title')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Ressource Concernée --}}
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0a2a43;">
                    Ressource Concernée (Optionnel)
                </label>
                <select name="resource_id" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;">
                    <option value="">-- Sélectionner une ressource --</option>
                    @foreach(\App\Models\Resource::all() as $resource)
                        <option value="{{ $resource->id }}" {{ old('resource_id') == $resource->id ? 'selected' : '' }}>{{ $resource->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Niveau de Priorité --}}
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0a2a43;">
                    Niveau de Priorité <span style="color: #ef4444;">*</span>
                </label>
                <select name="priority" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;" required>
                    <option value="">-- Sélectionner la priorité --</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse - Pas d'urgence</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Moyen - Problème récurrent</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Élevée - Impact Important</option>
                    <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critique - Service indisponible</option>
                </select>
                @error('priority')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Description Détaillée --}}
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0a2a43;">
                    Description Détaillée <span style="color: #ef4444;">*</span>
                    <small style="color: #6b7280; font-weight: 400;"> (minimum 50 caractères)</small>
                </label>
                <textarea name="description" placeholder="Décrivez précisément le problème rencontré, les actions que vous avez effectuées, les messages d'erreur reçus..." style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem; resize: vertical; min-height: 150px;" required>{{ old('description') }}</textarea>
                @error('description')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Boutons d'action --}}
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" style="flex: 1; padding: 0.75rem; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: background-color 0.3s ease;">
                    Signaler l'Incident
                </button>
                <a href="{{ route('dashboard') }}" style="flex: 1; padding: 0.75rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center; font-size: 0.95rem;">
                    Annuler
                </a>
            </div>

            {{-- Message informatif --}}
            <div style="background-color: #fef3c7; color: #92400e; padding: 1rem; border-radius: 6px; border-left: 4px solid #f59e0b; margin-top: 1rem;">
                <strong>⚠️ Important:</strong> Les incidents critiques (service indisponible) seront traités en priorité. Assurez-vous de fournir une description complète pour un traitement plus rapide.
            </div>
        </form>
    </div>
</div>
@endsection
