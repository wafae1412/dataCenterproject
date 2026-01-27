@extends('layouts.app')

@section('content')

<div style="max-width: 1000px; margin: 40px auto; padding: 20px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #e5e7eb;">
        <h1 style="color: #0a2a43; margin: 0;">Détails Maintenance</h1>
        @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
            <a href="{{ route('maintenances.edit', $maintenance->id) }}" style="padding: 10px 20px; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Modifier</a>
        @endif
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #10b981;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Content --}}
    <div style="background: white; border-radius: 10px; padding: 30px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        
        {{-- Status Badge --}}
        <div style="margin-bottom: 30px;">
            @php
                $statusColors = [
                    'scheduled' => ['bg' => '#fef3c7', 'color' => '#92400e'],
                    'in_progress' => ['bg' => '#dbeafe', 'color' => '#0c2340'],
                    'completed' => ['bg' => '#d1fae5', 'color' => '#065f46'],
                    'cancelled' => ['bg' => '#fee2e2', 'color' => '#7f1d1d'],
                ];
                $statusConfig = $statusColors[$maintenance->status] ?? ['bg' => '#e5e7eb', 'color' => '#374151'];
            @endphp
            <span style="display: inline-block; padding: 6px 12px; background-color: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['color'] }}; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                {{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}
            </span>
        </div>

        {{-- Details Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 30px;">
            
            {{-- Titre --}}
            <div style="border-left: 4px solid #3429d3; padding-left: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Titre</label>
                <p style="margin: 0; color: #0a2a43; font-size: 1.1rem; font-weight: 600;">{{ $maintenance->title }}</p>
            </div>

            {{-- Type --}}
            <div style="border-left: 4px solid #3429d3; padding-left: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Type</label>
                <p style="margin: 0; color: #0a2a43; font-size: 1.1rem; font-weight: 600;">{{ ucfirst($maintenance->type) }}</p>
            </div>

            {{-- Ressource --}}
            @if($maintenance->resource)
            <div style="border-left: 4px solid #3429d3; padding-left: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Ressource</label>
                <p style="margin: 0; color: #0a2a43; font-size: 1.1rem; font-weight: 600;">
                    <a href="{{ route('resources.show', $maintenance->resource->id) }}" style="color: #3429d3; text-decoration: none;">
                        {{ $maintenance->resource->name }}
                    </a>
                </p>
            </div>
            @endif

        </div>

        {{-- Dates Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 30px;">
            
            {{-- Date Début --}}
            <div style="border-left: 4px solid #f59e0b; padding-left: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Date Début</label>
                <p style="margin: 0; color: #0a2a43; font-size: 1.1rem; font-weight: 600;">{{ $maintenance->start_date->format('d/m/Y H:i') }}</p>
            </div>

            {{-- Date Fin --}}
            <div style="border-left: 4px solid #10b981; padding-left: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Date Fin</label>
                <p style="margin: 0; color: #0a2a43; font-size: 1.1rem; font-weight: 600;">{{ $maintenance->end_date->format('d/m/Y H:i') }}</p>
            </div>

            {{-- Durée Estimée --}}
            @if($maintenance->estimated_duration)
            <div style="border-left: 4px solid #3b82f6; padding-left: 15px;">
                <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Durée Estimée</label>
                <p style="margin: 0; color: #0a2a43; font-size: 1.1rem; font-weight: 600;">{{ $maintenance->estimated_duration }} heures</p>
            </div>
            @endif
        </div>

        {{-- Description --}}
        @if($maintenance->description)
        <div style="background-color: #f9fafb; padding: 15px; border-radius: 6px; margin-bottom: 30px; border-left: 4px solid #3429d3;">
            <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Description</label>
            <p style="margin: 0; color: #1f2937; line-height: 1.6;">{{ $maintenance->description }}</p>
        </div>
        @endif

        {{-- Notes --}}
        @if($maintenance->notes)
        <div style="background-color: #fef3c7; padding: 15px; border-radius: 6px; margin-bottom: 30px; border-left: 4px solid #f59e0b;">
            <label style="display: block; font-weight: 600; color: #92400e; margin-bottom: 8px; font-size: 0.85rem; text-transform: uppercase;">Notes</label>
            <p style="margin: 0; color: #78350f; line-height: 1.6;">{{ $maintenance->notes }}</p>
        </div>
        @endif

    </div>

    {{-- Actions --}}
    <div style="margin-top: 30px; display: flex; gap: 12px;">
        <a href="{{ route('maintenances.index') }}" style="padding: 12px 24px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block;">
            Retour à la liste
        </a>
        @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
            <form method="POST" action="{{ route('maintenances.destroy', $maintenance->id) }}" style="display: inline; margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Êtes-vous sûr ?')" style="padding: 12px 24px; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                    Supprimer
                </button>
            </form>
        @endif
    </div>
</div>

@endsection
