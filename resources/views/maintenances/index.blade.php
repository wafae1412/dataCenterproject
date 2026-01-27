@extends('layouts.app')

@section('title', 'Liste des Maintenances')

@section('content')

<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
        <div>
            <h1 style="color: #0a2a43; margin: 0 0 10px 0;">Maintenances</h1>
            <p style="color: #6b7280; margin: 0;">Total: <strong>{{ $maintenances->count() }}</strong> maintenance(s)</p>
        </div>
        
        @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
            <a href="{{ route('maintenances.create') }}" style="padding: 10px 20px; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">
                + Nouvelle maintenance
            </a>
        @endif
    </div>

    {{-- Message de succès --}}
    @if(session('success'))
    <div style="background-color: #d1fae5; color: #065f46; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #10b981;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Tableau --}}
    @if($maintenances->isEmpty())
        <div style="background-color: white; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <h3 style="color: #0a2a43;">Aucune maintenance</h3>
            <p style="color: #6b7280;">Planifiez votre première maintenance</p>
            <a href="{{ route('maintenances.create') }}" style="display: inline-block; padding: 10px 20px; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; margin-top: 10px;">
                Planifier une maintenance
            </a>
        </div>
    @else
        <div style="background-color: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #0a2a43; color: white;">
                        <th style="padding: 12px; text-align: left;">ID</th>
                        <th style="padding: 12px; text-align: left;">Ressource</th>
                        <th style="padding: 12px; text-align: left;">Titre</th>
                        <th style="padding: 12px; text-align: left;">Type</th>
                        <th style="padding: 12px; text-align: left;">Début</th>
                        <th style="padding: 12px; text-align: left;">Fin</th>
                        <th style="padding: 12px; text-align: left;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintenances as $maintenance)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px;">{{ $maintenance->id }}</td>
                        <td style="padding: 12px;">
                            @if($maintenance->resource)
                                <a href="{{ route('resources.show', $maintenance->resource->id) }}" style="color: #3429d3; text-decoration: none;">
                                    {{ $maintenance->resource->name }}
                                </a>
                            @else
                                <span style="color: #9ca3af;">Ressource supprimée</span>
                            @endif
                        </td>
                        <td style="padding: 12px;">{{ $maintenance->title }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; background-color: #f3f4f6; border-radius: 4px; font-size: 12px;">
                                {{ ucfirst($maintenance->type) }}
                            </span>
                        </td>
                        <td style="padding: 12px;">
                            {{ $maintenance->start_date ? $maintenance->start_date->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td style="padding: 12px;">
                            {{ $maintenance->end_date ? $maintenance->end_date->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;
                                @if($maintenance->status == 'scheduled') background-color: #fef3c7; color: #92400e;
                                @elseif($maintenance->status == 'in_progress') background-color: #dbeafe; color: #0c2340;
                                @elseif($maintenance->status == 'completed') background-color: #d1fae5; color: #065f46;
                                @else background-color: #fee2e2; color: #7f1d1d;
                                @endif
                            ">
                                @if($maintenance->status == 'scheduled')
                                    Planifiée
                                @elseif($maintenance->status == 'in_progress')
                                    En cours
                                @elseif($maintenance->status == 'completed')
                                    Terminée
                                @else
                                    Annulée
                                @endif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Lien retour --}}
    <div style="margin-top: 30px;">
        <a href="{{ route('resources.index') }}" style="color: #3429d3; text-decoration: none;">
            ← Retour aux ressources
        </a>
    </div>
</div>

@endsection