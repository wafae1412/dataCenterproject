@extends('layouts.app')

@section('content')
<div class="admin-dashboard-container">
    <div class="dashboard-header">
        <h1><i class="fas fa-shield-alt"></i> Tableau de Bord Administrateur</h1>
        <p class="subtitle">Bienvenue, <strong>{{ Auth::user()->name }}</strong>! Gestion complète du système DataCenter.</p>
    </div>

    <!-- Statistiques Principales -->
    <div class="stats-grid stats-grid-2col">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-database"></i></div>
            <div class="stat-content">
                <p class="stat-label">Ressources Totales</p>
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
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <p class="stat-label">Utilisateurs</p>
                <p class="stat-value">{{ $stats['total_users'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-chart-pie"></i></div>
            <div class="stat-content">
                <p class="stat-label">Taux Occupation</p>
                <p class="stat-value">{{ $stats['occupation_rate'] }}%</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-content">
                <p class="stat-label">Réservations Totales</p>
                <p class="stat-value">{{ $stats['total_reservations'] }}</p>
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
    <div class="admin-sections">
        <div class="admin-section">
            <div class="section-header">
                <div>
                    <h2><i class="fas fa-cog"></i> Gestion du Système</h2>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;">Accédez aux outils d'administration principaux</p>
                </div>
            </div>
            <div class="admin-links">
                <a href="{{ route('admin.users') }}" class="admin-link-card">
                    <div class="link-icon"><i class="fas fa-user-shield"></i></div>
                    <h3>Utilisateurs</h3>
                    <p>Créer, modifier ou supprimer les utilisateurs du système</p>
                </a>
                <a href="{{ route('resources.index') }}" class="admin-link-card">
                    <div class="link-icon"><i class="fas fa-server"></i></div>
                    <h3>Ressources</h3>
                    <p>Gérer les serveurs et ressources disponibles</p>
                </a>
                <a href="{{ route('reservations.index') }}" class="admin-link-card">
                    <div class="link-icon"><i class="fas fa-calendar-check"></i></div>
                    <h3>Réservations</h3>
                    <p>Valider, rejeter ou modifier les réservations</p>
                </a>
                <a href="{{ route('categories.index') }}" class="admin-link-card">
                    <div class="link-icon"><i class="fas fa-folder-open"></i></div>
                    <h3>Catégories</h3>
                    <p>Organiser et gérer les catégories de ressources</p>
                </a>
            </div>
        </div>

        <!-- Réservations Récentes -->
        <div class="admin-section">
            <div class="section-header">
                <div>
                    <h2><i class="fas fa-list"></i> Réservations Récentes</h2>
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
                <div class="admin-table" style="overflow-x: auto;">
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
