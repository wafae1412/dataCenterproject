@extends('layouts.app')

@section('content')
<div class="admin-dashboard-container">
    <div class="dashboard-header">
        <h1>🔐 Dashboard Admin</h1>
        <p class="subtitle">Bienvenue, {{ Auth::user()->name }}! Gestion complète du système.</p>
    </div>

    <!-- Statistiques Principales -->
    <div class="stats-grid stats-grid-2col">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-content">
                <p class="stat-label">Ressources Totales</p>
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
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <p class="stat-label">Utilisateurs</p>
                <p class="stat-value">{{ $stats['total_users'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <p class="stat-label">Taux Occupation</p>
                <p class="stat-value">{{ $stats['occupation_rate'] }}%</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-content">
                <p class="stat-label">Réservations Totales</p>
                <p class="stat-value">{{ $stats['total_reservations'] }}</p>
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
    <div class="admin-sections">
        <div class="admin-section">
            <div class="section-header">
                <h2>⚙️ Gestion du Système</h2>
            </div>
            <div class="admin-links">
                <a href="{{ route('admin.users') }}" class="admin-link-card">
                    <div class="link-icon">👥</div>
                    <h3>Utilisateurs</h3>
                    <p>Créer, modifier ou supprimer des utilisateurs</p>
                </a>
                <a href="{{ route('resources.index') }}" class="admin-link-card">
                    <div class="link-icon">📦</div>
                    <h3>Ressources</h3>
                    <p>Gérer les serveurs et ressources disponibles</p>
                </a>
                <a href="{{ route('reservations.index') }}" class="admin-link-card">
                    <div class="link-icon">📋</div>
                    <h3>Réservations</h3>
                    <p>Valider ou rejeter les réservations</p>
                </a>
                <a href="{{ route('categories.index') }}" class="admin-link-card">
                    <div class="link-icon">📂</div>
                    <h3>Catégories</h3>
                    <p>Organiser les catégories de ressources</p>
                </a>
            </div>
        </div>

        <!-- Réservations Récentes -->
        <div class="admin-section">
            <div class="section-header">
                <h2>📅 Réservations Récentes</h2>
                <a href="{{ route('reservations.index') }}" class="btn btn-small btn-primary">Voir Tout</a>
            </div>

            @if($recent_reservations->isEmpty())
                <div class="empty-state">
                    <p>Aucune réservation récente.</p>
                </div>
            @else
                <div class="admin-table">
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

    <!-- Card 3: Statistiques -->
    <a href="#" style="text-decoration:none; color:inherit;">
        <div class="card" style="padding:20px; border:1px solid #ccc; border-radius:10px; width:200px; text-align:center; transition:0.3s; cursor:pointer;">
            <h3>Statistiques</h3>
            <p>Vue globale du Data Center</p>
        </div>
    </a>

</div>

<!-- Petit CSS pour hover effect -->
<style>
.cards .card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transform: translateY(-5px);
}
</style>

@endsection
