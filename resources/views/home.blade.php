@extends('layouts.app')

@section('content')

<div style="max-width: 900px; margin: 40px auto; padding: 20px;">
    {{-- Welcome Header --}}
    <div style="background: linear-gradient(135deg, #3429d3 0%, #0a2a43 100%); color: white; padding: 40px; border-radius: 10px; margin-bottom: 30px;">
        <h1 style="margin: 0 0 10px 0;">Bienvenue, {{ auth()->user()->name }}!</h1>
        <p style="margin: 0; opacity: 0.9;">Vous êtes connecté à l'application DataCenter</p>
    </div>

    {{-- Status Message --}}
    @if (session('status'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 15px; margin-bottom: 30px; border-radius: 6px; border-left: 4px solid #10b981;">
            {{ session('status') }}
        </div>
    @endif

    {{-- User Info Card --}}
    <div style="background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden; margin-bottom: 30px;">
        <div style="background-color: #f9fafb; padding: 20px; border-bottom: 1px solid #e5e7eb;">
            <h2 style="margin: 0; color: #0a2a43;">Informations du compte</h2>
        </div>
        <div style="padding: 30px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Nom</p>
                    <p style="margin: 0; font-size: 18px; font-weight: 600; color: #0a2a43;">{{ auth()->user()->name }}</p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Email</p>
                    <p style="margin: 0; font-size: 18px; font-weight: 600; color: #0a2a43;">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Rôle</p>
                    <p style="margin: 0; font-size: 18px; font-weight: 600; color: #0a2a43;">
                        <span style="padding: 4px 12px; background-color: #3429d3; color: white; border-radius: 20px; display: inline-block;">
                            {{ auth()->user()->role->name ?? 'N/A' }}
                        </span>
                    </p>
                </div>
                <div>
                    <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">Inscrit depuis</p>
                    <p style="margin: 0; font-size: 18px; font-weight: 600; color: #0a2a43;">
                        @if(auth()->user()->created_at)
                            {{ auth()->user()->created_at->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <a href="{{ route('resources.index') }}" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); text-decoration: none; border-left: 4px solid #3429d3; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.1)'">
            <h3 style="margin: 0 0 10px 0; color: #0a2a43;">📦 Ressources</h3>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">Gérez vos ressources DataCenter</p>
        </a>

        <a href="{{ route('maintenances.index') }}" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); text-decoration: none; border-left: 4px solid #10b981; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.1)'">
            <h3 style="margin: 0 0 10px 0; color: #0a2a43;">🔧 Maintenances</h3>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">Planifiez et suivez les maintenances</p>
        </a>

        <a href="{{ route('reservations.index') }}" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); text-decoration: none; border-left: 4px solid #f59e0b; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(0, 0, 0, 0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.1)'">
            <h3 style="margin: 0 0 10px 0; color: #0a2a43;">📅 Réservations</h3>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">Gérez vos réservations</p>
        </a>
    </div>
</div>

@endsection
