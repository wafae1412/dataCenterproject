@extends('layouts.app')

@section('title', 'Planifier Maintenance')

@section('content')

<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h1 style="color: #0a2a43; margin-bottom: 20px;">Planifier une Maintenance</h1>

    <p style="margin-bottom: 20px;">
        <a href="{{ url()->previous() }}" style="color: #3429d3; text-decoration: none;">← Retour</a>
    </p>

    {{-- Messages d'erreur --}}
    @if($errors->any())
    <div style="background-color: #fee2e2; color: #7f1d1d; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #ef4444;">
        <strong>Erreurs:</strong>
        <ul style="margin-top: 10px; margin-bottom: 0;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Message succès --}}
    @if(session('success'))
    <div style="background-color: #d1fae5; color: #065f46; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #10b981;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Formulaire --}}
    <form action="{{ route('maintenances.store') }}" method="POST" id="maintenanceForm" style="background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        @csrf

        {{-- Ressource --}}
        <div style="margin-bottom: 25px;">
            <h3 style="color: #0a2a43; font-size: 18px; margin-bottom: 15px;">Ressource à maintenir</h3>
            
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">
                    Ressource <span style="color: #ef4444;">*</span>
                </label>
                <select name="resource_id" id="resource_id" required style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                    <option value="">-- Sélectionner une ressource --</option>
                    @foreach($resources as $resource)
                    <option value="{{ $resource->id }}"
                            {{ (old('resource_id') ?? request('resource_id')) == $resource->id ? 'selected' : '' }}>
                        {{ $resource->name }} ({{ $resource->category->name ?? 'N/A' }}) - {{ $resource->status }}
                    </option>
                    @endforeach
                </select>
                @error('resource_id')
                <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Détails --}}
        <div style="margin-bottom: 25px;">
            <h3 style="color: #0a2a43; font-size: 18px; margin-bottom: 15px;">Détails de la maintenance</h3>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">
                    Titre <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                @error('title')
                <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Description</label>
                <textarea name="description" id="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; font-family: inherit;">{{ old('description') }}</textarea>
                @error('description')
                <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">
                    Type de maintenance <span style="color: #ef4444;">*</span>
                </label>
                <select name="type" id="type" required style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                    <option value="">-- Choisir un type --</option>
                    <option value="preventive" {{ old('type') == 'preventive' ? 'selected' : '' }}>Préventive</option>
                    <option value="corrective" {{ old('type') == 'corrective' ? 'selected' : '' }}>Corrective</option>
                    <option value="emergency" {{ old('type') == 'emergency' ? 'selected' : '' }}>Urgence</option>
                    <option value="upgrade" {{ old('type') == 'upgrade' ? 'selected' : '' }}>Mise à niveau</option>
                </select>
                @error('type')
                <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Dates --}}
        <div style="margin-bottom: 25px;">
            <h3 style="color: #0a2a43; font-size: 18px; margin-bottom: 15px;">Planification</h3>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">
                    Date de début <span style="color: #ef4444;">*</span>
                </label>
                <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" required style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                @error('start_date')
                <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">
                    Date de fin <span style="color: #ef4444;">*</span>
                </label>
                <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" required style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                @error('end_date')
                <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Durée estimée (heures)</label>
                <input type="number" name="estimated_duration" id="estimated_duration" value="{{ old('estimated_duration') }}" min="1" max="720" style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
                @error('estimated_duration')
                <div style="color: #ef4444; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Notes --}}
        <div style="margin-bottom: 25px;">
            <h3 style="color: #0a2a43; font-size: 18px; margin-bottom: 15px;">Notes supplémentaires</h3>
            
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Notes internes</label>
                <textarea name="notes" id="notes" rows="2" style="width: 100%; padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; font-family: inherit;">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Boutons --}}
        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" style="flex: 1; padding: 12px 20px; background-color: #3429d3; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                Planifier la maintenance
            </button>
            <a href="{{ route('resources.index') }}" style="flex: 1; padding: 12px 20px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; text-align: center; font-weight: 600;">
                Annuler
            </a>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/maintenances/create.js') }}"></script>
@endsection