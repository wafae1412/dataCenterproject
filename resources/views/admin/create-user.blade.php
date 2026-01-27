@extends('layouts.app')

@section('content')

<div style="max-width: 600px; margin: 40px auto; padding: 20px;">
    <div style="background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden;">
        
        {{-- Card Header --}}
        <div style="background: linear-gradient(135deg, #3429d3 0%, #2318c0 100%); color: white; padding: 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Créer un Utilisateur</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Ajouter un nouvel utilisateur au système</p>
        </div>

        {{-- Card Body --}}
        <div style="padding: 40px;">
            @if($errors->any())
                <div style="background-color: #fee2e2; color: #7f1d1d; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #ef4444;">
                    <strong>Erreurs:</strong>
                    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                {{-- Name --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Nom <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem; transition: border-color 0.3s ease, box-shadow 0.3s ease;"
                        onmouseover="this.style.borderColor='#3429d3'; this.style.boxShadow='0 0 0 3px rgba(52, 41, 211, 0.1)';"
                        onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                        required>
                    @error('name')
                        <small style="display: block; color: #ef4444; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Email --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Email <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem; transition: border-color 0.3s ease, box-shadow 0.3s ease;"
                        onmouseover="this.style.borderColor='#3429d3'; this.style.boxShadow='0 0 0 3px rgba(52, 41, 211, 0.1)';"
                        onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                        required>
                    @error('email')
                        <small style="display: block; color: #ef4444; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Password --}}
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Mot de passe <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="password" name="password" 
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem; transition: border-color 0.3s ease, box-shadow 0.3s ease;"
                        onmouseover="this.style.borderColor='#3429d3'; this.style.boxShadow='0 0 0 3px rgba(52, 41, 211, 0.1)';"
                        onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                        required>
                    @error('password')
                        <small style="display: block; color: #ef4444; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Role --}}
                <div style="margin-bottom: 30px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">
                        Rôle <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="role_id" 
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem; transition: border-color 0.3s ease, box-shadow 0.3s ease;"
                        onmouseover="this.style.borderColor='#3429d3'; this.style.boxShadow='0 0 0 3px rgba(52, 41, 211, 0.1)';"
                        onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                        required>
                        <option value="">-- Sélectionner un rôle --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <small style="display: block; color: #ef4444; margin-top: 4px;">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div style="display: flex; gap: 12px; margin-top: 30px;">
                    <button type="submit" style="flex: 1; padding: 12px; background-color: #3429d3; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#2318c0'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.backgroundColor='#3429d3'; this.style.transform='translateY(0)';">
                        Créer l'utilisateur
                    </button>
                    <a href="{{ route('admin.users') }}" style="flex: 1; padding: 12px; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center; transition: background-color 0.3s ease;"
                        onmouseover="this.style.backgroundColor='#4b5563';"
                        onmouseout="this.style.backgroundColor='#6b7280';">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
