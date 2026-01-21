 @extends('layouts.app')

@section('content')
<div class="responsable-dashboard-container">

    <div class="dashboard-header">
        <h1>📊 Dashboard Responsable</h1>
        <p class="subtitle">Gestion des ressources et réservations</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-content">
                <p class="stat-label">Ressources</p>
                <p class="stat-value">{{ $stats['total_resources'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <p class="stat-label">Disponibles</p>
                <p class="stat-value">{{ $stats['available_resources'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-content">
                <p class="stat-label">Occupées</p>
                <p class="stat-value">{{ $stats['occupied_resources'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🔧</div>
            <div class="stat-content">
                <p class="stat-label">En Maintenance</p>
                <p class="stat-value">{{ $stats['maintenance_resources'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <p class="stat-label">Réservations Actives</p>
                <p class="stat-value">{{ $stats['active_reservations'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⏸️</div>
            <div class="stat-content">
                <p class="stat-label">En Attente</p>
                <p class="stat-value">{{ $stats['pending_reservations'] }}</p>
            </div>
        </div>
    </div>

    <!-- Gestion -->
    <div class="responsable-sections">

        <div class="responsable-section">
            <h2>🛠️ Gestion</h2>

            <div class="responsable-links">
                <a href="{{ route('resources.index') }}" class="responsable-link-card">
                    <div class="link-icon">📦</div>
                    <h3>Ressources</h3>
                    <p>Consulter et gérer les ressources</p>
                </a>

                <a href="{{ route('maintenances.index') }}" class="responsable-link-card">
                    <div class="link-icon">🔧</div>
                    <h3>Maintenances</h3>
                    <p>Planifier les opérations de maintenance</p>
                </a>
            </div>
        </div>

        <!-- Réservations -->
        <div class="responsable-section">
            <div class="section-header">
                <h2>📋 Réservations Récentes</h2>
                <a href="{{ route('reservations.index') }}" class="btn btn-small btn-primary">
                    Voir tout
                </a>
            </div>

            @if($recent_reservations->isEmpty())
                <p>Aucune réservation récente.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Ressource</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->user->name }}</td>
                                <td>{{ $reservation->resource->name }}</td>
                                <td>{{ $reservation->date_start }}</td>
                                <td>{{ $reservation->date_end }}</td>
                                <td>
                                    <span class="status-{{ $reservation->status }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>
@endsection
