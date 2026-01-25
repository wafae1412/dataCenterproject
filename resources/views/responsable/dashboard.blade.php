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

    <!-- Sections de gestion -->
    <div class="responsable-sections">
        <div class="responsable-section">
            <div class="section-header">
                <h2>🛠️ Gestion des Ressources</h2>
            </div>
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

        <!-- Réservations à Traiter -->
        <div class="responsable-section">
            <div class="section-header">
                <h2>📋 Réservations Récentes</h2>
                <a href="{{ route('reservations.index') }}" class="btn btn-small btn-primary">Voir Tout</a>
            </div>

            @if($recent_reservations->isEmpty())
                <div class="empty-state">
                    <p>Aucune réservation récente.</p>
                </div>
            @else
                <div class="responsable-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Ressource</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->user->name }}</td>
                                    <td>{{ $reservation->resource->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $reservation->status }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-small btn-info">Voir</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

    </div>
</div>
@endsection


    
</body>
</html>
