@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="admin-dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1><i class="fas fa-shield-alt"></i> Tableau de Bord Administrateur</h1>
            <p class="subtitle">Bienvenue, <strong>{{ Auth::user()->name }}</strong>. Vue d'ensemble du système.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-server"></i></div>
            <div class="stat-content">
                <p class="stat-label">Ressources</p>
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
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <p class="stat-label">Utilisateurs</p>
                <p class="stat-value">{{ $stats['total_users'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-content">
                <p class="stat-label">Réservations</p>
                <p class="stat-value">{{ $stats['total_reservations'] }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-tools"></i></div>
            <div class="stat-content">
                <p class="stat-label">Maintenances</p>
                <p class="stat-value">{{ $stats['total_maintenances'] }}</p>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
        <!-- Réservations Récentes -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="fas fa-history"></i> Activité Récente</h2>
                <a href="{{ route('reservations.index') }}" class="btn btn-primary btn-small">
                    Tout Voir <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            @if($recent_reservations->isEmpty())
                <div class="empty-state">
                    <p>Aucune activité récente.</p>
                </div>
            @else
                <div class="table-container" style="margin-bottom: 0;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Ressource</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_reservations as $reservation)
                                <tr>
                                    <td><strong>{{ $reservation->user->name }}</strong></td>
                                    <td>{{ $reservation->resource->name }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $reservation->status }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info btn-small">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Liens Rapides -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="fas fa-link"></i> Gestion Rapide</h2>
            </div>
            <div class="admin-links" style="grid-template-columns: 1fr;">
                <a href="{{ route('admin.users') }}" class="admin-link-card">
                    <div class="link-icon"><i class="fas fa-users-cog"></i></div>
                    <h3>Utilisateurs</h3>
                    <p>Gérer les comptes et rôles.</p>
                </a>
                <a href="{{ route('resources.index') }}" class="admin-link-card">
                    <div class="link-icon"><i class="fas fa-database"></i></div>
                    <h3>Ressources</h3>
                    <p>Ajouter ou modifier des ressources.</p>
                </a>
                <a href="{{ route('categories.index') }}" class="admin-link-card">
                    <div class="link-icon"><i class="fas fa-tags"></i></div>
                    <h3>Catégories</h3>
                    <p>Structurer l'inventaire.</p>
                </a>
                <a href="{{ route('maintenances.index') }}" class="admin-link-card">
                    <div class="link-icon"><i class="fas fa-wrench"></i></div>
                    <h3>Maintenances</h3>
                    <p>Planifier et suivre les opérations.</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Maintenances Récentes -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-history"></i> Maintenances Récentes / à Venir</h2>
            <a href="{{ route('maintenances.index') }}" class="btn btn-primary btn-small">
                Tout Voir <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($recent_maintenances->isEmpty())
            <div class="empty-state">
                <p>Aucune maintenance récente.</p>
            </div>
        @else
            <div class="table-container" style="margin-bottom: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ressource</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Début</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_maintenances as $maintenance)
                            <tr>
                                <td>{{ $maintenance->resource->name ?? 'N/A' }}</td>
                                <td><a href="{{ route('maintenances.show', $maintenance) }}">{{ $maintenance->title }}</a></td>
                                <td><span class="badge badge-{{ $maintenance->type }}">{{ ucfirst($maintenance->type) }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($maintenance->start_date)->format('d/m/Y H:i') }}</td>
                                <td><span class="status-badge status-{{ $maintenance->status }}">{{ $maintenance->status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
@section('styles')
<style>
    @media (max-width: 1024px) {
        div[style*="grid-template-columns: 2fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection