@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 2rem auto; padding: 2rem;">
    <!-- En-tête -->
    <div style="margin-bottom: 2rem;">
        <h1 style="color: #0a2a43; margin-bottom: 0.5rem; font-size: 2.5rem;">📊 Tableau de Bord Admin</h1>
        <p style="color: #6b7280; font-size: 1rem;">Bienvenue, <strong>{{ Auth::user()->name }}</strong>! Voici un aperçu de votre système.</p>
    </div>

    <!-- Statistiques - Grille Professionnelle -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- Carte 1: Ressources Totales -->
        <div style="background: linear-gradient(135deg, #3429d3 0%, #2318c0 100%); color: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(52, 41, 211, 0.2);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Ressources</p>
                    <p style="margin: 0; font-size: 2.5rem; font-weight: 700;">{{ $stats['total_resources'] }}</p>
                </div>
                <span style="font-size: 2.5rem;">📦</span>
            </div>
        </div>

        <!-- Carte 2: Ressources Disponibles -->
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Disponibles</p>
                    <p style="margin: 0; font-size: 2.5rem; font-weight: 700;">{{ $stats['available_resources'] }}</p>
                </div>
                <span style="font-size: 2.5rem;">✅</span>
            </div>
        </div>

        <!-- Carte 3: Ressources Réservées -->
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Réservées</p>
                    <p style="margin: 0; font-size: 2.5rem; font-weight: 700;">{{ $stats['reserved_resources'] }}</p>
                </div>
                <span style="font-size: 2.5rem;">⏳</span>
            </div>
        </div>

        <!-- Carte 4: En Maintenance -->
        <div style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">En Maintenance</p>
                    <p style="margin: 0; font-size: 2.5rem; font-weight: 700;">{{ $stats['maintenance_resources'] }}</p>
                </div>
                <span style="font-size: 2.5rem;">🔧</span>
            </div>
        </div>

        <!-- Carte 5: Utilisateurs -->
        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Utilisateurs</p>
                    <p style="margin: 0; font-size: 2.5rem; font-weight: 700;">{{ $stats['total_users'] }}</p>
                </div>
                <span style="font-size: 2.5rem;">👥</span>
            </div>
        </div>

        <!-- Carte 6: Réservations Actives -->
        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border-radius: 10px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Réservations Actives</p>
                    <p style="margin: 0; font-size: 2.5rem; font-weight: 700;">{{ $stats['active_reservations'] }}</p>
                </div>
                <span style="font-size: 2.5rem;">📋</span>
            </div>
        </div>
    </div>

    <!-- Résumé des Statistiques -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- Taux d'Occupation -->
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border-left: 4px solid #f59e0b;">
            <h3 style="margin: 0 0 1rem 0; color: #0a2a43;">Taux d'Occupation</h3>
            <p style="margin: 0; font-size: 2.5rem; font-weight: 700; color: #f59e0b;">{{ $stats['occupation_rate'] }}%</p>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; color: #6b7280;">Des ressources sont occupées</p>
        </div>

        <!-- Maintenances Actives -->
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border-left: 4px solid #ef4444;">
            <h3 style="margin: 0 0 1rem 0; color: #0a2a43;">Maintenances Actives</h3>
            <p style="margin: 0; font-size: 2.5rem; font-weight: 700; color: #ef4444;">{{ $stats['active_maintenances'] }}</p>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; color: #6b7280;">Actuellement en cours</p>
        </div>

        <!-- Réservations en Attente -->
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border-left: 4px solid #3b82f6;">
            <h3 style="margin: 0 0 1rem 0; color: #0a2a43;">Réservations en Attente</h3>
            <p style="margin: 0; font-size: 2.5rem; font-weight: 700; color: #3b82f6;">{{ $stats['pending_reservations'] }}</p>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem; color: #6b7280;">À approuver</p>
        </div>
    </div>

    <!-- Section Gestion Maintenance -->
    <div style="background: white; border-radius: 10px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #0a2a43; margin-top: 0;">Gestion des Maintenances</h2>
        
        <!-- Boutons -->
        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
            <a href="/maintenances/create" style="padding: 0.75rem 1.5rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Nouvelle Maintenance</a>
            <a href="/maintenances" style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Liste des Maintenances</a>
        </div>
        
        <!-- Liste des Maintenances Actives -->
        @if(isset($active_maintenances) && $active_maintenances->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #0a2a43; color: white;">
                            <th style="padding: 1rem; text-align: left;">Ressource</th>
                            <th style="padding: 1rem; text-align: left;">Titre</th>
                            <th style="padding: 1rem; text-align: left;">Type</th>
                            <th style="padding: 1rem; text-align: left;">Début</th>
                            <th style="padding: 1rem; text-align: left;">Fin</th>
                            <th style="padding: 1rem; text-align: left;">Statut</th>
                            <th style="padding: 1rem; text-align: left;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($active_maintenances as $maintenance)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 0.75rem 1rem;">{{ $maintenance->resource->name ?? 'N/A' }}</td>
                            <td style="padding: 0.75rem 1rem;">{{ $maintenance->title }}</td>
                            <td style="padding: 0.75rem 1rem;">{{ $maintenance->type }}</td>
                            <td style="padding: 0.75rem 1rem;">{{ $maintenance->start_date->format('d/m/Y H:i') }}</td>
                            <td style="padding: 0.75rem 1rem;">{{ $maintenance->end_date ? $maintenance->end_date->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td style="padding: 0.75rem 1rem;">
                                @if($maintenance->status == 'scheduled')
                                    <span style="display: inline-block; background-color: #fef3c7; color: #92400e; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">Planifiée</span>
                                @elseif($maintenance->status == 'in_progress')
                                    <span style="display: inline-block; background-color: #dbeafe; color: #0c2340; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">En cours</span>
                                @elseif($maintenance->status == 'completed')
                                    <span style="display: inline-block; background-color: #d1fae5; color: #065f46; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">Terminée</span>
                                @else
                                    <span style="display: inline-block; background-color: #fee2e2; color: #7f1d1d; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">Annulée</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('maintenances.show', $maintenance->id) }}" style="padding: 0.5rem 1rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-size: 0.85rem;">Voir</a>
                                    <a href="{{ route('maintenances.edit', $maintenance->id) }}" style="padding: 0.5rem 1rem; background-color: #f59e0b; color: white; text-decoration: none; border-radius: 6px; font-size: 0.85rem;">Modifier</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p style="color: #6b7280;">Aucune maintenance active.</p>
        @endif
    </div>

    <!-- Section Gestion Système -->
    <div style="background: white; border-radius: 10px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #0a2a43; margin-top: 0;">Gestion du Système</h2>
        <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
            <a href="{{ route('admin.users') }}" style="padding: 0.75rem 1.5rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Utilisateurs</a>
            <a href="{{ route('resources.index') }}" style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Ressources</a>
            <a href="{{ route('maintenances.index') }}" style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Maintenances</a>
            <a href="{{ route('reservations.index') }}" style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Réservations</a>
            <a href="{{ route('categories.index') }}" style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Catégories</a>
        </div>
    </div>

    <!-- Réservations Récentes -->
    <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="color: #0a2a43; margin: 0;">Réservations Récentes</h2>
            <a href="{{ route('reservations.index') }}" style="padding: 0.5rem 1rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">Voir Tout</a>
        </div>
        
        @if($recent_reservations->isEmpty())
            <p style="color: #6b7280;">Aucune réservation récente.</p>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #0a2a43; color: white;">
                            <th style="padding: 1rem; text-align: left;">Utilisateur</th>
                            <th style="padding: 1rem; text-align: left;">Ressource</th>
                            <th style="padding: 1rem; text-align: left;">Début</th>
                            <th style="padding: 1rem; text-align: left;">Fin</th>
                            <th style="padding: 1rem; text-align: left;">Statut</th>
                            <th style="padding: 1rem; text-align: left;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_reservations as $reservation)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 0.75rem 1rem;">{{ $reservation->user->name }}</td>
                            <td style="padding: 0.75rem 1rem;">{{ $reservation->resource->name }}</td>
                            <td style="padding: 0.75rem 1rem;">{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</td>
                            <td style="padding: 0.75rem 1rem;">{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}</td>
                            <td style="padding: 0.75rem 1rem;">
                                <span style="display: inline-block; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600;
                                    @if($reservation->status == 'pending') background-color: #fef3c7; color: #92400e;
                                    @elseif($reservation->status == 'approved') background-color: #d1fae5; color: #065f46;
                                    @elseif($reservation->status == 'rejected') background-color: #fee2e2; color: #7f1d1d;
                                    @else background-color: #dbeafe; color: #0c2340;
                                    @endif
                                ">{{ ucfirst($reservation->status) }}</span>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <a href="{{ route('reservations.show', $reservation->id) }}" style="padding: 0.5rem 1rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-size: 0.85rem;">Voir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
