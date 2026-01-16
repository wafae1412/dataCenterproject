@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Bienvenue, {{ Auth::user()->name }}!</h1>
        <p class="subtitle">Dashboard Utilisateur</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-content">
                <p class="stat-label">Mes Réservations</p>
                <p class="stat-value">{{ $stats['my_reservations'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <p class="stat-label">Actives</p>
                <p class="stat-value">{{ $stats['active_reservations'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-content">
                <p class="stat-label">En Attente</p>
                <p class="stat-value">{{ $stats['pending_reservations'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✓</div>
            <div class="stat-content">
                <p class="stat-label">Terminées</p>
                <p class="stat-value">{{ $stats['finished_reservations'] }}</p>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <!-- Mes Réservations Récentes -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Mes Réservations Récentes</h2>
                <a href="{{ route('reservations.index') }}" class="btn btn-small btn-primary">Voir Tout</a>
            </div>

            @if($my_reservations->isEmpty())
                <div class="empty-state">
                    <p>Aucune réservation pour le moment.</p>
                </div>
            @else
                <div class="reservations-list">
                    @foreach($my_reservations as $reservation)
                        <div class="reservation-item">
                            <div class="item-header">
                                <h3>{{ $reservation->resource->name }}</h3>
                                <span class="status-badge status-{{ $reservation->status }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                            <p class="item-date">
                                📅 {{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }} 
                                → {{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}
                            </p>
                            <p class="item-justification">{{ substr($reservation->justification, 0, 100) }}...</p>
                            <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-small btn-info">Voir Détails</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Ressources Disponibles -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Ressources Disponibles</h2>
                <a href="{{ route('reservations.create') }}" class="btn btn-small btn-success">+ Nouvelle Réservation</a>
            </div>

            @if($available_resources->isEmpty())
                <div class="empty-state">
                    <p>Aucune ressource disponible pour le moment.</p>
                </div>
            @else
                <div class="resources-grid">
                    @foreach($available_resources as $resource)
                        <div class="resource-card">
                            <div class="resource-header">
                                <h3>{{ $resource->name }}</h3>
                                <span class="resource-category">{{ $resource->category->name }}</span>
                            </div>
                            <div class="resource-specs">
                                <div class="spec">
                                    <span class="spec-icon">⚙️</span>
                                    <span>{{ $resource->cpu }} CPU</span>
                                </div>
                                <div class="spec">
                                    <span class="spec-icon">💾</span>
                                    <span>{{ $resource->ram }}GB RAM</span>
                                </div>
                                <div class="spec">
                                    <span class="spec-icon">📀</span>
                                    <span>{{ $resource->storage }}GB</span>
                                </div>
                            </div>
                            @if($resource->description)
                                <p class="resource-description">{{ substr($resource->description, 0, 80) }}...</p>
                            @endif
                            <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-block">Réserver</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
