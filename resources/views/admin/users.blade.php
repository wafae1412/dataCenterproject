@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Gestion des Utilisateurs</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if($users->isEmpty())
        <div class="empty-state">
            <i class="fas fa-users" style="font-size:3rem; margin-bottom:1rem; opacity:0.5;"></i>
            <h3>Aucun utilisateur</h3>
            <p>Il n'y a actuellement aucun utilisateur dans le système.</p>
        </div>
    @else
        <div class="table-container">
            <table class="table users-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Utilisateur</th>
                        <th>Rôle</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div style="font-weight: 600; color: var(--primary);">{{ $user->name }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-light);">{{ $user->email }}</div>
                            </td>
                            <td>
                                <span class="role-badge role-{{ strtolower($user->role->name) }}">
                                    {{ $user->role->name }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div style="display: flex; gap: 1rem; align-items: center;">
                                    <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" style="display: flex; align-items: center; gap: 0.5rem;">
                                        @csrf
                                        <select name="role_id" style="padding: 0.35rem; border-radius: 6px; border: 1px solid var(--border); font-size: 0.85rem;">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-small btn-info" title="Mettre à jour le rôle">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Êtes-vous sûr ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection