@extends('layouts.app')

@section('content')

<div style="max-width: 800px; margin: 40px auto; padding: 20px;">
    <div style="background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden;">
        
        {{-- Card Header --}}
        <div style="background: linear-gradient(135deg, #3429d3 0%, #2318c0 100%); color: white; padding: 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Modifier Maintenance</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">{{ $maintenance->title }}</p>
        </div>

        {{-- Card Body --}}
        <div style="padding: 40px;">
            @if($errors->any())
                <div style="background-color: #fee2e2; color: #7f1d1d; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #ef4444;">
                    <strong>Erreurs:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('maintenances.update', $maintenance->id) }}">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Titre <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $maintenance->title) }}"
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit;"
                        required>
                </div>

                {{-- Description --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Description
                    </label>
                    <textarea name="description" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; min-height: 100px;">{{ old('description', $maintenance->description) }}</textarea>
                </div>

                {{-- Type --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Type <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="type" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit;">
                        <option value="preventive" {{ old('type', $maintenance->type) == 'preventive' ? 'selected' : '' }}>Préventive</option>
                        <option value="corrective" {{ old('type', $maintenance->type) == 'corrective' ? 'selected' : '' }}>Corrective</option>
                        <option value="emergency" {{ old('type', $maintenance->type) == 'emergency' ? 'selected' : '' }}>Urgence</option>
                        <option value="upgrade" {{ old('type', $maintenance->type) == 'upgrade' ? 'selected' : '' }}>Mise à jour</option>
                    </select>
                </div>

                {{-- Start Date --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Date Début <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', $maintenance->start_date->format('Y-m-d\TH:i')) }}"
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit;"
                        required>
                </div>

                {{-- End Date --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Date Fin <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date', $maintenance->end_date->format('Y-m-d\TH:i')) }}"
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit;"
                        required>
                </div>

                {{-- Estimated Duration --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Durée Estimée (heures)
                    </label>
                    <input type="number" name="estimated_duration" value="{{ old('estimated_duration', $maintenance->estimated_duration) }}"
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit;"
                        min="1" max="720">
                </div>

                {{-- Notes --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Notes
                    </label>
                    <textarea name="notes" style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; min-height: 100px;">{{ old('notes', $maintenance->notes) }}</textarea>
                </div>

                {{-- Status --}}
                <div style="margin-bottom: 30px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Statut <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="status" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit;">
                        <option value="scheduled" {{ old('status', $maintenance->status) == 'scheduled' ? 'selected' : '' }}>Planifiée</option>
                        <option value="in_progress" {{ old('status', $maintenance->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="completed" {{ old('status', $maintenance->status) == 'completed' ? 'selected' : '' }}>Complétée</option>
                        <option value="cancelled" {{ old('status', $maintenance->status) == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div style="display: flex; gap: 12px;">
                    <button type="submit" style="flex: 1; padding: 12px; background-color: #3429d3; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                        Mettre à jour
                    </button>
                    <a href="{{ route('maintenances.show', $maintenance->id) }}" style="flex: 1; padding: 12px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center;">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
