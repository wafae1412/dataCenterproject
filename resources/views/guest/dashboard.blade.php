@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 2rem auto; padding: 2rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="color: #0a2a43; margin: 0 0 0.5rem 0; font-size: 2rem;">Bienvenue, Guest!</h1>
        <p style="color: #6b7280; font-size: 1rem; margin-top: 0.5rem;">Dashboard Invité - Consultation Uniquement</p>
    </div>

    <!-- Statistiques -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #3429d3;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">📦</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Ressources Totales</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['total_resources'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #10b981;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">✅</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Disponibles</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['available_resources'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #f59e0b;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">🔧</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">En Maintenance</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['maintenance_resources'] }}</p>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; align-items: center; gap: 1.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease; border-left: 4px solid #ef4444;">
            <div style="font-size: 2.5rem; min-width: 60px; text-align: center;">📋</div>
            <div style="flex: 1;">
                <p style="color: #6b7280; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem;">Maintenances</p>
                <p style="font-size: 2rem; font-weight: 700; color: #0a2a43;">{{ $stats['total_maintenances'] }}</p>
            </div>
        </div>
    </div>

    <div style="display: grid; gap: 2rem;">
        <!-- Ressources Disponibles -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
                <h2 style="color: #0a2a43; margin: 0;">Ressources Disponibles</h2>
                <a href="{{ route('resources.index') }}" style="padding: 0.5rem 1rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">Voir Tous</a>
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
                                <h3 style="margin: 0 0 0.5rem 0; color: #0a2a43;">{{ $resource->name }}</h3>
                                <span style="display: inline-block; background-color: #3429d3; color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">{{ $resource->category->name ?? 'N/A' }}</span>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1rem; padding: 1rem 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; font-size: 0.9rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span>📍</span>
                                    <span><strong>Localisation:</strong> {{ $resource->localisation ?? 'Non spécifiée' }}</span>
                                </div>
                            </div>

                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">{{ substr($resource->description, 0, 100) }}...</p>

                            <a href="{{ route('resources.show', $resource->id) }}" style="display: block; padding: 0.75rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center; font-size: 0.85rem;">Voir Détails</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Listes des Maintenances -->
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
                <h2 style="color: #0a2a43; margin: 0;">Maintenances Programmées</h2>
                <a href="{{ route('maintenances.index') }}" style="padding: 0.5rem 1rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem;">Voir Tous</a>
            </div>

            @if($upcoming_maintenances->isEmpty())
                <div style="text-align: center; padding: 3rem 2rem; color: #6b7280;">
                    <p>Aucune maintenance prévue pour le moment.</p>
                </div>
            @else
                <div style="display: grid; gap: 1.5rem;">
                    @foreach($upcoming_maintenances as $maintenance)
                        <div style="background: white; border-left: 4px solid #f59e0b; padding: 1.5rem; border-radius: 6px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <h3 style="margin: 0;">{{ $maintenance->title }}</h3>
                                <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; background-color: #fef3c7; color: #92400e;">
                                    {{ $maintenance->status == 'scheduled' ? 'Planifiée' : ($maintenance->status == 'in_progress' ? 'En cours' : 'Terminée') }}
                                </span>
                            </div>
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.75rem;">
                                <strong>Ressource:</strong> 
                                @if($maintenance->resource)
                                    <a href="{{ route('resources.show', $maintenance->resource->id) }}" style="color: #3429d3; text-decoration: none;">{{ $maintenance->resource->name }}</a>
                                @else
                                    <span>N/A</span>
                                @endif
                            </p>
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.75rem;">
                                📅 {{ \Carbon\Carbon::parse($maintenance->start_date)->format('d/m/Y H:i') }} 
                                → {{ \Carbon\Carbon::parse($maintenance->end_date)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
