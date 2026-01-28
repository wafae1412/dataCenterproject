@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1><i class="fas fa-home"></i> Tableau de Bord</h1>
            <p class="subtitle">Bienvenue, <strong>{{ Auth::user()->name }}</strong>. Voici un aperçu de vos activités.</p>
        </div>
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
            <div class="stat-icon"><i class="fas fa-check-double"></i></div>
            <div class="stat-content">
                <p class="stat-label">Terminées</p>
                <p class="stat-value">{{ $stats['finished_reservations'] }}</p>
            </div>
        </div>
    </div>

    <!-- Mes Réservations Récentes -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-list-alt"></i> Mes Réservations Récentes</h2>
            <a href="{{ route('reservations.index') }}" class="btn btn-primary btn-small">
                Voir Tout <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($my_reservations->isEmpty())
            <div class="empty-state">
                <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <p>Aucune réservation récente.</p>
                <a href="{{ route('reservations.create') }}" class="btn btn-success" style="margin-top: 1rem;">
                    <i class="fas fa-plus"></i> Nouvelle Réservation
                </a>
            </div>
        @else
            <div class="reservations-list">
                @foreach($my_reservations as $reservation)
                    <div class="reservation-item">
                        <div class="item-info">
                            <h3>{{ $reservation->resource->name }}</h3>
                            <div class="item-meta">
                                <span><i class="fas fa-folder"></i> {{ $reservation->resource->category->name }}</span>
                                <span><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <span class="status-badge status-{{ $reservation->status }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                            <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info btn-small">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Maintenances en cours -->
    @inject('maintenanceModel', 'App\Models\Maintenance')
    @php
        $activeMaintenances = $maintenanceModel::with('resource')
            ->where('status', 'in_progress')
            ->orderBy('end_date', 'asc')
            ->get();
    @endphp

    @if($activeMaintenances->isNotEmpty())
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-tools"></i> Ressources en Maintenance</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ressource</th>
                        <th>Type</th>
                        <th>Fin prévue</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeMaintenances as $maintenance)
                        <tr>
                            <td><strong>{{ $maintenance->resource->name }}</strong></td>
                            <td>{{ ucfirst($maintenance->type) }}</td>
                            <td>{{ \Carbon\Carbon::parse($maintenance->end_date)->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-maintenance">En cours</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Ressources Disponibles -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-server"></i> Ressources Disponibles</h2>
            <a href="{{ route('resources.index') }}" class="btn btn-primary btn-small">
                Catalogue <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($available_resources->isEmpty())
            <div class="empty-state">
                <i class="fas fa-server" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
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
                            <div class="spec"><span class="spec-icon"><i class="fas fa-microchip"></i></span> <strong>{{ $resource->cpu }}</strong> Cores</div>
                            <div class="spec"><span class="spec-icon"><i class="fas fa-memory"></i></span> <strong>{{ $resource->ram }}</strong> GB</div>
                        </div>
                        <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" class="btn btn-success btn-block">
                            <i class="fas fa-calendar-plus"></i> Réserver
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection