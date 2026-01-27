@extends('layouts.app')

@section('title', $resource->name ?? 'Ressource')

@section('content')

<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
        <div>
            <h1 style="color: #0a2a43; margin: 0 0 15px 0;">{{ $resource->name ?? 'Ressource' }}</h1>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <span style="background-color: #3429d3; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px;">
                    ID: {{ $resource->id }}
                </span>
                <span style="background-color: #6b7280; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px;">
                    {{ $resource->category->name ?? 'N/A' }}
                </span>
                <span style="padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600;
                    @if($resource->status == 'available') background-color: #d1fae5; color: #065f46;
                    @elseif($resource->status == 'reserved') background-color: #fecaca; color: #7f1d1d;
                    @elseif($resource->status == 'maintenance') background-color: #fef3c7; color: #92400e;
                    @else background-color: #e5e7eb; color: #374151;
                    @endif
                ">
                    {{ ucfirst($resource->status) }}
                </span>
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
                <a href="{{ route('resources.edit', $resource->id) }}" style="padding: 10px 20px; background-color: #f59e0b; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Éditer</a>
            @endif
            @if($resource->status == 'available')
                @if(auth()->user()->role->name !== 'Guest')
                    <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" style="padding: 10px 20px; background-color: #10b981; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Réserver</a>
                @endif
            @endif
            <a href="{{ route('resources.index') }}" style="padding: 10px 20px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Retour</a>
        </div>
    </div>

    {{-- Spécifications --}}
    <div style="background-color: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #0a2a43; margin-top: 0;">Spécifications Techniques</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div>
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">CPU</label>
                <div style="font-size: 18px; font-weight: bold; color: #0a2a43;">{{ $resource->cpu ?? 'N/A' }} cores</div>
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">RAM</label>
                <div style="font-size: 18px; font-weight: bold; color: #0a2a43;">{{ $resource->ram ?? 'N/A' }} GB</div>
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Stockage</label>
                <div style="font-size: 18px; font-weight: bold; color: #0a2a43;">{{ $resource->storage ?? 'N/A' }} GB</div>
            </div>
            
            <div>
                <label style="display: block; font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Localisation</label>
                <div style="font-size: 18px; font-weight: bold; color: #0a2a43;">{{ $resource->location ?? 'Non spécifiée' }}</div>
            </div>
        </div>

        @if($resource->description)
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <h3 style="color: #0a2a43; margin-top: 0;">Description</h3>
            <p style="color: #6b7280; line-height: 1.6;">{{ $resource->description }}</p>
        </div>
        @endif
    </div>

    {{-- Réservations --}}
    @if($resource->reservations && $resource->reservations->count() > 0)
    <div style="background-color: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #0a2a43; margin-top: 0;">Réservations ({{ $resource->reservations->count() }})</h2>
        
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; text-align: left; font-weight: 600;">Utilisateur</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600;">Début</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600;">Fin</th>
                    <th style="padding: 12px; text-align: left; font-weight: 600;">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resource->reservations as $reservation)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $reservation->user->name ?? 'N/A' }}</td>
                    <td style="padding: 12px;">{{ $reservation->date_start ? $reservation->date_start->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td style="padding: 12px;">{{ $reservation->date_end ? $reservation->date_end->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;
                            @if($reservation->status == 'pending') background-color: #fef3c7; color: #92400e;
                            @elseif($reservation->status == 'approved') background-color: #d1fae5; color: #065f46;
                            @elseif($reservation->status == 'rejected') background-color: #fee2e2; color: #7f1d1d;
                            @else background-color: #dbeafe; color: #0c2340;
                            @endif
                        ">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Maintenances --}}
    <div style="background-color: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: #0a2a43; margin: 0;">Maintenances</h2>
            @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
                @php
                    $hasActiveMaintenance = $resource->maintenances()->whereIn('status', ['scheduled', 'in_progress'])->exists();
                @endphp
                @if(!$hasActiveMaintenance)
                    <a href="{{ route('maintenances.create', ['resource_id' => $resource->id]) }}" style="padding: 10px 20px; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">+ Planifier</a>
                @else
                    <span style="padding: 10px 20px; background-color: #9ca3af; color: white; border-radius: 6px; font-weight: 600; display: inline-block; cursor: not-allowed;">Maintenance en cours</span>
                @endif
            @endif
        </div>
        
        @if($resource->maintenances && $resource->maintenances->count() > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f3f4f6; border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Titre</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Type</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Début</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Fin</th>
                        <th style="padding: 12px; text-align: left; font-weight: 600;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resource->maintenances as $maintenance)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px;">
                            <a href="{{ route('maintenances.show', $maintenance->id) }}" style="color: #3429d3; text-decoration: none;">
                                {{ $maintenance->title }}
                            </a>
                        </td>
                        <td style="padding: 12px;">{{ ucfirst($maintenance->type) }}</td>
                        <td style="padding: 12px;">{{ $maintenance->start_date ? $maintenance->start_date->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td style="padding: 12px;">{{ $maintenance->end_date ? $maintenance->end_date->format('d/m/Y H:i') : 'N/A' }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;
                                @if($maintenance->status == 'scheduled') background-color: #fef3c7; color: #92400e;
                                @elseif($maintenance->status == 'in_progress') background-color: #dbeafe; color: #0c2340;
                                @elseif($maintenance->status == 'completed') background-color: #d1fae5; color: #065f46;
                                @else background-color: #fee2e2; color: #7f1d1d;
                                @endif
                            ">
                                {{ ucfirst($maintenance->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #6b7280; text-align: center; padding: 20px;">Aucune maintenance planifiée.</p>
        @endif
    </div>
</div>

@endsection
