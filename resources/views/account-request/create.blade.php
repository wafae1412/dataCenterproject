@extends('layouts.app')

@section('content')
<div style="max-width: 700px; margin: 3rem auto; padding: 2rem;">
    <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 2rem;">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="color: #0a2a43; margin: 0 0 0.5rem 0; font-size: 1.75rem;">Demande d'Ouverture de Compte</h1>
            <p style="color: #6b7280; margin: 0;">Remplissez ce formulaire pour demander l'accès au système</p>
        </div>

        @if(session('success'))
            <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; margin-bottom: 1.5rem; border-radius: 6px; border-left: 4px solid #10b981;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #fee2e2; color: #7f1d1d; padding: 1rem; margin-bottom: 1.5rem; border-radius: 6px; border-left: 4px solid #ef4444;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('account-request.store') }}" style="display: grid; gap: 1.5rem;">
            @csrf

            {{-- Nom Complet --}}
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0a2a43;">
                    Nom Complet <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Jean Dupont" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;" required>
                @error('name')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0a2a43;">
                    Adresse Email <span style="color: #ef4444;">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="jean.dupont@university.edu" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;" required>
                @error('email')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Institution --}}
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0a2a43;">
                    Institution / Université <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="institution" value="{{ old('institution') }}" placeholder="Université de Casablanca" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;" required>
                @error('institution')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Département/Faculté --}}
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0a2a43;">
                    Département / Faculté <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="department" value="{{ old('department') }}" placeholder="Informatique / Génie des Données" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;" required>
                @error('department')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Justification --}}
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #0a2a43;">
                    Justification de l'Accès <span style="color: #ef4444;">*</span>
                    <small style="color: #6b7280; font-weight: 400;"> (minimum 50 caractères)</small>
                </label>
                <textarea name="justification" placeholder="Décrivez pourquoi vous avez besoin d'accès à nos ressources informatiques..." style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem; resize: vertical; min-height: 120px;" required>{{ old('justification') }}</textarea>
                @error('justification')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            {{-- Boutons d'action --}}
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" style="flex: 1; padding: 0.75rem; background-color: #3429d3; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.95rem; transition: background-color 0.3s ease;">
                    Soumettre la Demande
                </button>
                <a href="{{ route('resources.index') }}" style="flex: 1; padding: 0.75rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center; font-size: 0.95rem;">
                    Annuler
                </a>
            </div>

            {{-- Message informatif --}}
            <div style="background-color: #dbeafe; color: #0c2340; padding: 1rem; border-radius: 6px; border-left: 4px solid #3b82f6; margin-top: 1rem;">
                <strong>ℹ️ Important:</strong> Votre demande sera examinée par les administrateurs du système. Vous recevrez une notification dès que votre compte sera activé.
            </div>
        </form>
    </div>
</div>
@endsection
