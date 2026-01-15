@extends('layouts.app')

@section('title', $resource->name)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/resources/show.css') }}">
@endsection

@section('content')
<div class="resources-show">
    <div class="page-header">
        <div class="header-info">
            <h1>{{ $resource->name }}</h1>
            <div class="resource-meta">
                <span class="badge">ID: {{ $resource->id }}</span>
                <span class="category-badge">{{ $resource->category->name }}</span>
                <span class="status-badge {{ $resource->status }}">{{ $resource->status }}</span>
                <span class="created-date">Créée le: {{ $resource->created_at->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="header-actions">
            <a href="{{ route('resources.edit', $resource) }}" class="btn edit">Modifier</a>
            <a href="{{ route('reservations.create.withResource', $resource->id) }}" class="btn reserve">Réserver</a>
            <a href="{{ route('resources.index') }}" class="btn secondary">Retour</a>
        </div>
    </div>

    <div class="resource-content">
        <div class="resource-grid">
            <div class="specs-card">
                <h2>Spécifications Techniques</h2>
                <div class="specs-list">
                    <div class="spec-item">
                        <span class="spec-label">CPU:</span>
                        <span class="spec-value">{{ $resource->cpu }} cores</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">RAM:</span>
                        <span class="spec-value">{{ $resource->ram }} GB</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Stockage:</span>
                        <span class="spec-value">{{ $resource->storage }} GB</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Localisation:</span>
                        <span class="spec-value">{{ $resource->location ?? 'Non spécifiée' }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Catégorie:</span>
                        <span class="spec-value">{{ $resource->category->name }}</span>
                    </div>
                </div>
            </div>

            <div class="description-card">
                <h2>Description</h2>
                <div class="description-content">
                    {{ $resource->description ?? 'Aucune description disponible.' }}
                </div>
            </div>
        </div>

        <div class="reservations-section">
            <h2>Réservations ({{ $resource->reservations->count() }})</h2>

            @if($resource->reservations->isEmpty())
            <div class="empty-reservations">
                <p>Aucune réservation pour cette ressource</p>
                <a href="{{ route('reservations.create.withResource', $resource->id) }}" class="btn primary">
                    Faire une réservation
                </a>
            </div>
            @else
            <div class="reservations-table">
                <table>
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resource->reservations->sortByDesc('date_start') as $reservation)
                        <tr>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->date_start->format('d/m/Y H:i') }}</td>
                            <td>{{ $reservation->date_end->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="reservation-status {{ $reservation->status }}">
                                    {{ $reservation->status }}
                                </span>
                            </td>
                            <td class="reservation-actions">
                                <a href="#" class="btn small view">Voir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <div class="maintenance-section">
            <h2>Historique de Maintenance</h2>
            <div class="maintenance-actions">
                <a href="{{ route('maintenance.create', $resource->id) }}" class="btn primary">
                    Ajouter une maintenance
                </a>
                <a href="{{ route('maintenances.index') }}" class="btn secondary">
                    Voir toutes les maintenances
                </a>
            </div>

            @if($resource->maintenances->isEmpty())
            <p class="no-maintenance">Aucune maintenance enregistrée</p>
            @else
            <div class="maintenance-list">
                @foreach($resource->maintenances->sortByDesc('date_start') as $maintenance)
                <div class="maintenance-item">
                    <div class="maintenance-date">
                        {{ $maintenance->date_start->format('d/m/Y') }} - {{ $maintenance->date_end->format('d/m/Y') }}
                    </div>
                    <div class="maintenance-desc">{{ $maintenance->description }}</div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="resource-actions">
            <form action="{{ route('resources.destroy', $resource) }}" method="POST"
                  onsubmit="return confirm('Supprimer cette ressource? Toutes les réservations seront annulées.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn delete">Supprimer la Ressource</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/resources/show.js') }}"></script>
@endsection
