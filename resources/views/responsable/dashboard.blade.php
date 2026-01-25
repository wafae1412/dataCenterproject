@extends('layouts.app')

@section('content')
<div class="responsable-dashboard-container">
    <div class="dashboard-header">
        <h1><i class="fas fa-user-tie"></i> Tableau de Bord Responsable</h1>
        <p class="subtitle">Bienvenue, <strong>{{ Auth::user()->name }}</strong>! Gestion centralisée des ressources et réservations.</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-database"></i></div>
            <div class="stat-content">
                <p class="stat-label">Total Ressources</p>
                <p class="stat-value">{{ $stats['total_resources'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <p class="stat-label">Disponibles</p>
                <p class="stat-value">{{ $stats['available_resources'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-content">
                <p class="stat-label">Occupées</p>
                <p class="stat-value">{{ $stats['occupied_resources'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-wrench"></i></div>
            <div class="stat-content">
                <p class="stat-label">En Maintenance</p>
                <p class="stat-value">{{ $stats['maintenance_resources'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-play-circle"></i></div>
            <div class="stat-content">
                <p class="stat-label">Réservations Actives</p>
                <p class="stat-value">{{ $stats['active_reservations'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
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
                <div>
                    <h2><i class="fas fa-tools"></i> Gestion des Ressources</h2>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;">Accédez aux outils de gestion des ressources et maintenances</p>
                </div>
            </div>
            <div class="responsable-links">
                <a href="{{ route('resources.index') }}" class="responsable-link-card">
                    <div class="link-icon"><i class="fas fa-server"></i></div>
                    <h3>Ressources</h3>
                    <p>Consulter, modifier et gérer l'état des ressources</p>
                </a>
                <a href="{{ route('maintenances.index') }}" class="responsable-link-card">
                    <div class="link-icon"><i class="fas fa-hammer"></i></div>
                    <h3>Maintenances</h3>
                    <p>Planifier et suivre les opérations de maintenance</p>
                </a>
            </div>
        </div>

        <!-- Réservations à Traiter -->
        <div class="responsable-section">
            <div class="section-header">
                <div>
                    <h2><i class="fas fa-clipboard-list"></i> Réservations Récentes</h2>
                </div>
                <a href="{{ route('reservations.index') }}" class="btn btn-primary btn-small">
                    <i class="fas fa-arrow-right"></i> Voir Tout
                </a>
            </div>

            @if($recent_reservations->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
                    <p>Aucune réservation récente.</p>
                </div>
            @else
                <div class="responsable-table" style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Utilisateur</th>
                                <th><i class="fas fa-database"></i> Ressource</th>
                                <th><i class="fas fa-calendar"></i> Début</th>
                                <th><i class="fas fa-calendar"></i> Fin</th>
                                <th><i class="fas fa-tag"></i> Statut</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_reservations as $reservation)
                                <tr>
                                    <td><strong>{{ $reservation->user->name }}</strong></td>
                                    <td>{{ $reservation->resource->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $reservation->status }}">
                                            <i class="fas fa-dot-circle"></i> {{ ucfirst(__('messages.'.$reservation->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info btn-small">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
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
@endsection
