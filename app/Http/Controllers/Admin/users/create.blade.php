 @extends('layouts.app')

@section('content')

<h2>Ajouter User</h2>

<form method="POST" action="{{ route('admin.users.store') }}">
@csrf

<input type="text" name="name" placeholder="Nom"><br><br>

<input type="email" name="email" placeholder="Email"><br><br>

<input type="password" name="password" placeholder="Password"><br><br>

<select name="role_id">
    @foreach($roles as $role)
        <option value="{{ $role->id }}">{{ $role->name }}</option>
    @endforeach
</select><br><br>

<button type="submit">Ajouter</button>

</form>

@endsection
