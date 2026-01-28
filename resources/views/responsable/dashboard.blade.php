@extends('layouts.app')

@section('title', 'Dashboard Responsable')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1><i class="fas fa-user-tie"></i> Tableau de Bord Responsable</h1>
            <p class="subtitle">Gestion des ressources et supervision.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-server"></i></div>
            <div class="stat-content">
                <p class="stat-label">Total Ressources</p>
                <p class="stat-value">{{ $stats['total_resources'] }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <p class="stat-label">Réservations Actives</p>
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
            <div class="stat-icon"><i class="fas fa-tools"></i></div>
            <div class="stat-content">
                <p class="stat-label">Maintenances en cours</p>
                <p class="stat-value">{{ $stats['in_progress_maintenances'] }}</p>
            </div>
        </div>
    </div>

    <!-- Gestion Rapide -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-bolt"></i> Gestion Rapide</h2>
        </div>
        <div class="admin-links">
            <a href="{{ route('resources.index') }}" class="admin-link-card">
                <div class="link-icon"><i class="fas fa-server"></i></div>
                <h3>Ressources</h3>
                <p>Gérer le parc et les disponibilités.</p>
            </a>
            <!-- Ajout du lien Réservations -->
            <a href="{{ route('reservations.index') }}" class="admin-link-card">
                <div class="link-icon"><i class="fas fa-calendar-alt"></i></div>
                <h3>Réservations</h3>
                <p>Valider les demandes en attente.</p>
            </a>
            <a href="{{ route('maintenances.index') }}" class="admin-link-card">
                <div class="link-icon"><i class="fas fa-tools"></i></div>
                <h3>Maintenances</h3>
                <p>Planifier les interventions.</p>
            </a>
        </div>
    </div>

    <!-- Recent Reservations Table -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-history"></i> Dernières Réservations</h2>
            <a href="{{ route('reservations.index') }}" class="btn btn-primary btn-small">Voir tout</a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Ressource</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->resource->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</td>
                            <td><span class="status-badge status-{{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span></td>
                            <td>
                                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-info btn-small"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Aucune réservation récente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection