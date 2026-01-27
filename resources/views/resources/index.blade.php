@extends('layouts.app')

@section('title', 'Ressources')

@section('content')

<div style="margin-bottom: 30px;">
    <h1>Ressources DataCenter</h1>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #10b981;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #fee2e2; color: #7f1d1d; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #ef4444;">
            {{ session('error') }}
        </div>
    @endif

    @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
        <a href="{{ route('resources.create') }}" style="display: inline-block; padding: 10px 20px; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; margin-bottom: 20px;">
            + Nouvelle Ressource
        </a>
    @endif
</div>

{{-- Filtres --}}
<div style="background-color: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
    <h3 style="margin-top: 0;">Filtres</h3>
    <form method="GET" action="{{ route('resources.index') }}" id="filter-form">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Catégorie</label>
                <select name="category_id" id="filter-category" style="width: 100%; padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px;">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Statut</label>
                <select name="status" id="filter-status" style="width: 100%; padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px;">
                    <option value="">Tous les statuts</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                        Disponible
                    </option>
                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>
                        Réservé
                    </option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                        Maintenance
                    </option>
                    <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>
                        Désactivé
                    </option>
                </select>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Recherche</label>
                <input type="text" name="search" id="filter-search" style="width: 100%; padding: 8px; border: 1px solid #e5e7eb; border-radius: 6px;"
                       value="{{ request('search') }}" placeholder="Nom...">
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="padding: 10px 20px; background-color: #3429d3; color: white; border: none; border-radius: 6px; cursor: pointer;">Filtrer</button>
            <a href="{{ route('resources.index') }}" style="padding: 10px 20px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px;">Reset</a>
        </div>
    </form>
</div>

{{-- Stats --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); text-align: center;">
        <div style="font-size: 32px; font-weight: bold; color: #3429d3;">{{ $resources->count() }}</div>
        <div style="color: #6b7280; font-size: 14px;">Total</div>
    </div>
    <div style="background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); text-align: center;">
        <div style="font-size: 32px; font-weight: bold; color: #10b981;">{{ $resources->where('status', 'available')->count() }}</div>
        <div style="color: #6b7280; font-size: 14px;">Disponibles</div>
    </div>
    <div style="background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); text-align: center;">
        <div style="font-size: 32px; font-weight: bold; color: #f59e0b;">{{ $resources->where('status', 'reserved')->count() }}</div>
        <div style="color: #6b7280; font-size: 14px;">Réservées</div>
    </div>
</div>

{{-- Tableau --}}
@if($resources->isEmpty())
    <div style="background-color: white; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h3>Aucune ressource</h3>
        <p style="color: #6b7280;">Créez votre première ressource</p>
        <a href="{{ route('resources.create') }}" style="display: inline-block; padding: 10px 20px; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px;">Créer ressource</a>
    </div>
@else
    <div style="background-color: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #0a2a43; color: white;">
                    <th style="padding: 12px; text-align: left;">ID</th>
                    <th style="padding: 12px; text-align: left;">Nom</th>
                    <th style="padding: 12px; text-align: left;">Catégorie</th>
                    <th style="padding: 12px; text-align: left;">Spécifications</th>
                    <th style="padding: 12px; text-align: left;">Statut</th>
                    <th style="padding: 12px; text-align: left;">Maintenances</th>
                    <th style="padding: 12px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $resource->id }}</td>
                    <td style="padding: 12px;">
                        <strong>{{ $resource->name }}</strong><br>
                        <small style="color: #6b7280;">{{ $resource->category->name ?? 'N/A' }}</small>
                    </td>
                    <td style="padding: 12px;">{{ $resource->category->name ?? 'N/A' }}</td>
                    <td style="padding: 12px; font-size: 14px;">
                        CPU: {{ $resource->cpu }} cores<br>
                        RAM: {{ $resource->ram }} GB<br>
                        Stockage: {{ $resource->storage }} GB
                    </td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;
                            @if($resource->status == 'available') background-color: #d1fae5; color: #065f46;
                            @elseif($resource->status == 'reserved') background-color: #fecaca; color: #7f1d1d;
                            @elseif($resource->status == 'maintenance') background-color: #fef3c7; color: #92400e;
                            @else background-color: #e5e7eb; color: #374151;
                            @endif
                        ">
                            {{ ucfirst($resource->status) }}
                        </span>
                    </td>
                    <td style="padding: 12px; font-size: 14px;">
                        @if($resource->maintenances && $resource->maintenances->count() > 0)
                            @foreach($resource->maintenances as $maintenance)
                                <div style="margin-bottom: 6px;">
                                    <strong>{{ $maintenance->title }}</strong><br>
                                    <small style="color: #6b7280;">{{ ucfirst($maintenance->status) }}</small>
                                </div>
                            @endforeach
                        @else
                            <small style="color: #9ca3af;">Aucune</small>
                        @endif
                    </td>
                    <td style="padding: 12px;">
                        <a href="{{ route('resources.show', $resource->id) }}" style="display: inline-block; padding: 6px 12px; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 4px; margin-right: 4px; font-size: 12px;">Voir</a>
                        @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
                            <a href="{{ route('resources.edit', $resource->id) }}" style="display: inline-block; padding: 6px 12px; background-color: #f59e0b; color: white; text-decoration: none; border-radius: 4px; margin-right: 4px; font-size: 12px;">Éditer</a>
                        @endif
                        @if($resource->status == 'available')
                            @if(auth()->user()->role->name !== 'Guest')
                                <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" style="display: inline-block; padding: 6px 12px; background-color: #10b981; color: white; text-decoration: none; border-radius: 4px; margin-right: 4px; font-size: 12px;">Réserver</a>
                            @endif
                        @endif
                        @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
                            @php
                                $hasActiveMaintenance = $resource->maintenances()->whereIn('status', ['scheduled', 'in_progress'])->exists();
                            @endphp
                            @if(!$hasActiveMaintenance)
                                <a href="{{ route('maintenances.create', ['resource_id' => $resource->id]) }}" style="display: inline-block; padding: 6px 12px; background-color: #3429d3; color: white; text-decoration: none; border-radius: 4px; font-size: 12px;">Planifier</a>
                            @else
                                <span style="display: inline-block; padding: 6px 12px; background-color: #9ca3af; color: white; border-radius: 4px; font-size: 12px; cursor: not-allowed;">Maintenance en cours</span>
                            @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 20px; text-align: center; color: #6b7280;">
                        Aucune ressource trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

@endsection

@section('scripts')
<script src="{{ asset('js/resources/index.js') }}"></script>
@endsection
