@extends('layouts.app')

@section('content')

<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
        <h1 style="margin: 0; color: #0a2a43;">Nouvelle Réservation</h1>
        <a href="{{ route('reservations.index') }}" style="color: #3429d3; text-decoration: none;">← Retour</a>
    </div>

    {{-- Errors --}}
    @if($errors->any())
        <div style="background-color: #fee2e2; color: #7f1d1d; padding: 15px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #ef4444;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li style="margin-bottom: 5px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div style="background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 30px; margin-bottom: 20px;">
        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf

            {{-- Resource Selection --}}
            <div style="margin-bottom: 25px;">
                <label for="resource_id" style="display: block; font-weight: 600; margin-bottom: 8px; color: #0a2a43;">
                    Ressource <span style="color: #ef4444;">*</span>
                </label>
                <select id="resource_id" name="resource_id" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;">
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

            {{-- Dates --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                <div>
                    <label for="date_start" style="display: block; font-weight: 600; margin-bottom: 8px; color: #0a2a43;">
                        Date/Heure Début <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="datetime-local" id="date_start" name="date_start" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;" value="{{ old('date_start') }}">
                    <small style="color: #6b7280; display: block; margin-top: 4px;">Format: jj/mm/aaaa hh:mm</small>
                </div>

                <div>
                    <label for="date_end" style="display: block; font-weight: 600; margin-bottom: 8px; color: #0a2a43;">
                        Date/Heure Fin <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="datetime-local" id="date_end" name="date_end" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;" value="{{ old('date_end') }}">
                    <small style="color: #6b7280; display: block; margin-top: 4px;">Doit être après la date de début</small>
                </div>
            </div>

            {{-- Justification --}}
            <div style="margin-bottom: 25px;">
                <label for="justification" style="display: block; font-weight: 600; margin-bottom: 8px; color: #0a2a43;">
                    Justification <span style="color: #ef4444;">*</span>
                </label>
                <textarea id="justification" name="justification" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; min-height: 120px; font-family: inherit;" placeholder="Expliquez l'objectif de cette réservation..." minlength="10" maxlength="500">{{ old('justification') }}</textarea>
                <small style="color: #6b7280; display: block; margin-top: 4px;">Entre 10 et 500 caractères</small>
            </div>

            {{-- Buttons --}}
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; padding: 12px; background-color: #3429d3; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#2318c0'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#3429d3'; this.style.transform='translateY(0)';">Créer la Réservation</button>
                <a href="{{ route('reservations.index') }}" style="flex: 1; padding: 12px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#4b5563'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#6b7280'; this.style.transform='translateY(0)';">Annuler</a>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div style="background-color: #dbeafe; border-left: 4px solid #3b82f6; padding: 20px; border-radius: 6px;">
        <h3 style="margin-top: 0; color: #0c2340;">⚠️ Règles de réservation</h3>
        <ul style="margin-bottom: 0; color: #0c2340; padding-left: 20px;">
            <li>La ressource doit être disponible pour la période demandée</li>
            <li>Une réservation doit être approuvée par un administrateur</li>
            <li>La justification doit être claire et complète</li>
        </ul>
    </div>
</div>

@endsection
