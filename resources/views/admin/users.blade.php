 @extends('layouts.app')

@section('content')

<h2>Gestion des utilisateurs</h2>

@if(session('success'))
    <div class="alert">
        {{ session('success') }}
    </div>
@endif

<table border="1" cellpadding="10">
    <tr>
        <th>Nom</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>

    @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role->name }}</td>
            <td>
                <!-- Formulaire pour modifier le rôle -->
                <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}" style="display:inline-block;">
                    @csrf
                    <select name="role_id">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">Modifier</button>
                </form>

                <!-- Formulaire pour supprimer l'utilisateur -->
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Vous êtes sûr?')">Supprimer</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>

@endsection
