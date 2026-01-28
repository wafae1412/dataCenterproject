@extends('layouts.app')

@section('title', 'Mon Profil')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
<style>
    /* Specific styles for profile forms */
    .profile-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
        padding: 2rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-text {
        font-size: 0.85rem;
        color: var(--text-light);
        margin-top: 0.25rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item label {
        display: block;
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 0.25rem;
    }

    .info-item div {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--primary);
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Mon Profil</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 1rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-card">
        <div class="info-grid">
            <div class="info-item">
                <label>Rôle</label>
                <div>
                    <span class="role-badge role-{{ strtolower($user->role->name ?? 'user') }}">
                        {{ $user->role->name ?? 'Utilisateur' }}
                    </span>
                </div>
            </div>
            <div class="info-item">
                <label>Membre depuis le</label>
                <div>{{ $user->created_at->format('d/m/Y') }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
