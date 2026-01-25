@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1><i class="fas fa-chart-line"></i> Tableau de Bord Personnel</h1>
        <p class="subtitle">Bienvenue, <strong>{{ Auth::user()->name }}</strong>! Gérez vos réservations de ressources.</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-cube"></i></div>
            <div class="stat-content">
                <p class="stat-label">Total Réservations</p>
                <p class="stat-value">{{ $stats['my_reservations'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <p class="stat-label">Actives</p>
                <p class="stat-value">{{ $stats['active_reservations'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-content">
                <p class="stat-label">En Attente</p>
                <p class="stat-value">{{ $stats['pending_reservations'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-flag-checkered"></i></div>
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
                <div>
                    <h2><i class="fas fa-list"></i> Mes Réservations Récentes</h2>
                </div>
                <a href="{{ route('reservations.index') }}" class="btn btn-primary btn-small">
                    <i class="fas fa-arrow-right"></i> Voir Tout
                </a>
            </div>

            @if($my_reservations->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                    <p>Aucune réservation pour le moment.</p>
                    <a href="{{ route('reservations.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Créer une réservation
                    </a>
                </div>
            @else
                <div class="reservations-list">
                    @foreach($my_reservations as $reservation)
                        <div class="reservation-item">
                            <div class="item-header">
                                <div>
                                    <h3>{{ $reservation->resource->name }}</h3>
                                </div>
                                <span class="status-badge status-{{ $reservation->status }}">
                                    <i class="fas fa-dot-circle"></i> {{ ucfirst(__('messages.'.$reservation->status)) }}
                                </span>
                            </div>
                            <p class="item-date">
                                <i class="fas fa-calendar-alt"></i>
                                <strong>{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</strong>
                                <i class="fas fa-arrow-right" style="margin: 0 0.5rem;"></i>
                                <strong>{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}</strong>
                            </p>
                            <p class="item-justification"><em>{{ substr($reservation->justification, 0, 120) }}{{ strlen($reservation->justification) > 120 ? '...' : '' }}</em></p>
                            <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info btn-small">
                                <i class="fas fa-eye"></i> Voir Détails
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Ressources Disponibles -->
        <div class="dashboard-section">
            <div class="section-header">
                <div>
                    <h2><i class="fas fa-server"></i> Ressources Disponibles</h2>
                </div>
                <a href="{{ route('reservations.create') }}" class="btn btn-success btn-small">
                    <i class="fas fa-plus"></i> Nouvelle Réservation
                </a>
            </div>

            @if($available_resources->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-server" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                    <p>Aucune ressource disponible pour le moment.</p>
                </div>
            @else
                <div class="resources-grid">
                    @foreach($available_resources as $resource)
                        <div class="resource-card">
                            <div class="resource-header">
                                <h3><i class="fas fa-box"></i> {{ $resource->name }}</h3>
                                <span class="resource-category">{{ $resource->category->name }}</span>
                            </div>
                            <div class="resource-specs">
                                <div class="spec">
                                    <span class="spec-icon"><i class="fas fa-microchip"></i></span>
                                    <span><strong>{{ $resource->cpu }}</strong> vCPU</span>
                                </div>
                                <div class="spec">
                                    <span class="spec-icon"><i class="fas fa-memory"></i></span>
                                    <span><strong>{{ $resource->ram }}</strong> GB RAM</span>
                                </div>
                                <div class="spec">
                                    <span class="spec-icon"><i class="fas fa-hdd"></i></span>
                                    <span><strong>{{ $resource->storage }}</strong> GB Storage</span>
                                </div>
                            </div>
                            @if($resource->description)
                                <p class="resource-description">{{ substr($resource->description, 0, 100) }}{{ strlen($resource->description) > 100 ? '...' : '' }}</p>
                            @endif
                            <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-calendar-check"></i> Réserver Maintenant
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
