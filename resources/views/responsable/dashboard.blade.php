@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 2rem auto; padding: 2rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="color: #0a2a43; margin: 0 0 0.5rem 0;">📊 Dashboard Responsable</h1>
        <p style="color: #6b7280;">Gestion des ressources et réservations</p>
    </div>

    <!-- Statistiques -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">📦</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Ressources</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['total_resources'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">✅</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Disponibles</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['available_resources'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">⏳</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Occupées</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['occupied_resources'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">🔧</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">En Maintenance</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['maintenance_resources'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">✅</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Réservations Actives</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['active_reservations'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">⏸️</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">En Attente</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['pending_reservations'] }}</p>
            </div>
        </div>
    </div>

    <!-- Sections de gestion -->
    <div style="display: grid; gap: 2rem;">
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <div style="margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
                <h2 style="color: #0a2a43; margin: 0;">🛠️ Gestion des Ressources</h2>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <a href="{{ route('resources.index') }}" style="display: block; background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 2rem; border-radius: 10px; text-decoration: none; color: inherit; border: 2px solid #e5e7eb; transition: all 0.3s ease;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem; display: block;">📦</div>
                    <h3 style="margin: 1rem 0 0.5rem 0; color: #0a2a43;">Ressources</h3>
                    <p style="color: #6b7280; font-size: 0.9rem;">Consulter et gérer les ressources</p>
                </a>
                <a href="/maintenances" style="display: block; background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 2rem; border-radius: 10px; text-decoration: none; color: inherit; border: 2px solid #e5e7eb; transition: all 0.3s ease;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem; display: block;">🔧</div>
                    <h3 style="margin: 1rem 0 0.5rem 0; color: #0a2a43;">Maintenances</h3>
                    <p style="color: #6b7280; font-size: 0.9rem;">Planifier les opérations de maintenance</p>
                </a>
            </div>
        </div>

        <!-- Réservations à Traiter -->
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
                <h2 style="color: #0a2a43; margin: 0;">📋 Réservations Récentes</h2>
                <a href="{{ route('reservations.index') }}" style="padding: 0.5rem 1rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">Voir Tout</a>
            </div>

            @if($recent_reservations->isEmpty())
                <div style="text-align: center; padding: 3rem 2rem; color: #6b7280;">
                    <p>Aucune réservation récente.</p>
                </div>
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
                                        <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;
                                            @if($reservation->status == 'pending') background-color: #fef3c7; color: #92400e;
                                            @elseif($reservation->status == 'approved') background-color: #d1fae5; color: #065f46;
                                            @elseif($reservation->status == 'rejected') background-color: #fee2e2; color: #7f1d1d;
                                            @else background-color: #dbeafe; color: #0c2340;
                                            @endif
                                        ">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem 1rem;">
                                        <a href="{{ route('reservations.show', $reservation->id) }}" style="padding: 0.5rem 1rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">Voir</a>
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
