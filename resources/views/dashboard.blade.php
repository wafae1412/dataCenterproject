@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 2rem auto; padding: 2rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="color: #0a2a43; margin: 0 0 0.5rem 0; font-size: 2rem;">Bienvenue, {{ Auth::user()->name }}!</h1>
        <p style="color: #6b7280; font-size: 1rem; margin-top: 0.5rem;">Dashboard Utilisateur</p>
    </div>

    <!-- Statistiques -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">📦</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Mes Réservations</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['my_reservations'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">✅</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Actives</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['active_reservations'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">⏳</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">En Attente</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['pending_reservations'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">✓</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Terminées</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['finished_reservations'] }}</p>
            </div>
        </div>
    </div>

    <div style="display: grid; gap: 2rem;">
        <!-- Mes Réservations Récentes -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
                <h2 style="color: #0a2a43; margin: 0;">Mes Réservations Récentes</h2>
                <a href="{{ route('reservations.index') }}" style="padding: 0.5rem 1rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">Voir Tout</a>
            </div>

            @if($my_reservations->isEmpty())
                <div style="text-align: center; padding: 3rem 2rem; color: #6b7280;">
                    <p>Aucune réservation pour le moment.</p>
                </div>
            @else
                <div style="display: grid; gap: 1.5rem;">
                    @foreach($my_reservations as $reservation)
                        <div style="background: white; border-left: 4px solid #3429d3; padding: 1.5rem; border-radius: 6px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <h3 style="margin: 0;">{{ $reservation->resource->name }}</h3>
                                <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;
                                    @if($reservation->status == 'pending') background-color: #fef3c7; color: #92400e;
                                    @elseif($reservation->status == 'approved') background-color: #d1fae5; color: #065f46;
                                    @elseif($reservation->status == 'rejected') background-color: #fee2e2; color: #7f1d1d;
                                    @else background-color: #dbeafe; color: #0c2340;
                                    @endif
                                ">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.75rem;">
                                📅 {{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }} 
                                → {{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}
                            </p>
                            <p style="color: #1f2937; margin-bottom: 1rem; line-height: 1.5;">{{ substr($reservation->justification, 0, 100) }}...</p>
                            <a href="{{ route('reservations.show', $reservation->id) }}" style="display: inline-block; padding: 0.5rem 1rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">Voir Détails</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Ressources Disponibles -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
                <h2 style="color: #0a2a43; margin: 0;">Ressources Disponibles</h2>
                <a href="{{ route('reservations.create') }}" style="padding: 0.5rem 1rem; background-color: #10b981; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">+ Nouvelle Réservation</a>
            </div>

            @if($available_resources->isEmpty())
                <div style="text-align: center; padding: 3rem 2rem; color: #6b7280;">
                    <p>Aucune ressource disponible pour le moment.</p>
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                    @foreach($available_resources as $resource)
                        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-top: 4px solid #3429d3;">
                            <div style="margin-bottom: 1rem;">
                                <h3 style="margin: 0 0 0.5rem 0;">{{ $resource->name }}</h3>
                                <span style="display: inline-block; background-color: #3429d3; color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">{{ $resource->category->name }}</span>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1rem; padding: 1rem 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                                    <span style="font-size: 1.2rem;">⚙️</span> {{ $resource->cpu }} CPU
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                                    <span style="font-size: 1.2rem;">💾</span> {{ $resource->ram }}GB RAM
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                                    <span style="font-size: 1.2rem;">📀</span> {{ $resource->storage }}GB
                                </div>
                            </div>
                            @if($resource->description)
                                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">{{ substr($resource->description, 0, 80) }}...</p>
                            @endif
                            <a href="{{ route('reservations.create') }}" style="display: block; width: 100%; padding: 0.75rem 1.5rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center;">Réserver</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Signaler un Incident -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 2rem; margin-top: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
                <h2 style="color: #0a2a43; margin: 0;">Support Technique</h2>
                <a href="{{ route('incident.create') }}" style="padding: 0.5rem 1rem; background-color: #ef4444; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">⚠️ Signaler un Incident</a>
            </div>
            <p style="color: #6b7280;">Rencontrez-vous un problème technique ou une erreur? N'hésitez pas à nous le signaler.</p>
        </div>
    </div>
</div>
@endsection
