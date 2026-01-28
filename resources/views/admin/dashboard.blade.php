@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="admin-dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord Administrateur</h1>
            <p class="subtitle">Vue d'ensemble et gestion du système.</p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <p class="stat-label">Utilisateurs</p>
                <p class="stat-value">{{ $stats['total_users'] }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-server"></i></div>
            <div class="stat-content">
                <p class="stat-label">Ressources</p>
                <p class="stat-value">{{ $stats['total_resources'] }}</p>
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
            <div class="stat-icon"><i class="fas fa-chart-pie"></i></div>
            <div class="stat-content">
                <p class="stat-label">Taux d'occupation</p>
                <p class="stat-value">{{ $stats['occupation_rate'] }}%</p>
            </div>
        </div>
    </div>

    <!-- Gestion Rapide -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-bolt"></i> Gestion Rapide</h2>
        </div>
        <div class="admin-links">
            <a href="{{ route('admin.users') }}" class="admin-link-card">
                <div class="link-icon"><i class="fas fa-users-cog"></i></div>
                <h3>Utilisateurs</h3>
                <p>Gérer les comptes et rôles.</p>
            </a>
            <a href="{{ route('resources.index') }}" class="admin-link-card">
                <div class="link-icon"><i class="fas fa-server"></i></div>
                <h3>Ressources</h3>
                <p>Gérer le parc informatique.</p>
            </a>
            <!-- Ajout du lien Réservations -->
            <a href="{{ route('reservations.index') }}" class="admin-link-card">
                <div class="link-icon"><i class="fas fa-calendar-alt"></i></div>
                <h3>Réservations</h3>
                <p>Gérer les demandes et plannings.</p>
            </a>
            <a href="{{ route('categories.index') }}" class="admin-link-card">
                <div class="link-icon"><i class="fas fa-tags"></i></div>
                <h3>Catégories</h3>
                <p>Organiser les types de ressources.</p>
            </a>
             <a href="{{ route('maintenances.index') }}" class="admin-link-card">
                <div class="link-icon"><i class="fas fa-tools"></i></div>
                <h3>Maintenances</h3>
                <p>Suivi des interventions techniques.</p>
            </a>
        </div>
    </div>

    <!-- Recent Reservations Table -->
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-history"></i> Réservations Récentes</h2>
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