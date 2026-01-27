@extends('layouts.app')

@section('content')

<div style="max-width: 1200px; margin: 2rem auto; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
        <h1 style="margin: 0; color: #0a2a43;">Gestion des utilisateurs</h1>
        <a href="{{ route('admin.users.create') }}" style="padding: 0.75rem 1.5rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">+ Nouvel Utilisateur</a>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; margin-bottom: 1.5rem; border-radius: 6px; border-left: 4px solid #10b981;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #0a2a43; color: white;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Nom</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Email</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Role</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem 1rem;">{{ $user->name }}</td>
                        <td style="padding: 0.75rem 1rem;">{{ $user->email }}</td>
                        <td style="padding: 0.75rem 1rem;">{{ $user->role->name }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" style="display: flex; gap: 0.5rem;">
                                    @csrf
                                    <select name="role_id" style="padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 0.9rem;">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" style="padding: 0.5rem 1rem; background-color: #f59e0b; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Modifier</button>
                                </form>

                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 0.5rem 1rem; background-color: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;" onclick="return confirm('Vous êtes sûr?')">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
