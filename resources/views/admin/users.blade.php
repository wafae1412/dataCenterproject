@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
@endsection

@section('content')
<div class="users-container">
    <div class="page-header">
        <h1>Gestion des Utilisateurs</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($users->isEmpty())
        <div class="empty-state">
            <h3>Aucun utilisateur trouvé</h3>
            <p>Il n'y a actuellement aucun utilisateur dans le système.</p>
        </div>
    @else
        <div class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="user-row" data-user-id="{{ $user->id }}">
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="user-name">{{ $user->name }}</div>
                            </td>
                            <td>
                                <div class="user-email">{{ $user->email }}</div>
                            </td>
                            <td>
                                <span class="role-badge role-{{ strtolower($user->role->name) }}">
                                    {{ $user->role->name }}
                                </span>
                            </td>
                            <td>
                                <div class="user-date">{{ $user->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="actions-cell">
                                <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" class="role-form">
                                    @csrf
                                    <div class="role-selector">
                                        <select name="role_id" class="role-select">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn-update">Modifier</button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
