@extends('layouts.app')

@section('content')

<div style="max-width: 800px; margin: 2rem auto; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
        <h1 style="color: #0a2a43; margin: 0;">Créer une Nouvelle Ressource</h1>
        <a href="{{ route('resources.index') }}" style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Retour à la liste</a>
    </div>

    <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <form action="{{ route('resources.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                        Nom de la ressource <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Ex: Serveur Web 01"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;">
                    @error('name')
                    <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                        Catégorie <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="category_id" required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                        CPU (cores) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="cpu" value="{{ old('cpu') }}" required min="1" max="128"
                           placeholder="Ex: 8"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;">
                    @error('cpu')
                    <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                        RAM (GB) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="ram" value="{{ old('ram') }}" required min="1" max="1024"
                           placeholder="Ex: 16"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;">
                    @error('ram')
                    <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                        Stockage (GB) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" name="storage" value="{{ old('storage') }}" required min="1" max="100000"
                           placeholder="Ex: 500"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;">
                    @error('storage')
                    <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                        Localisation
                    </label>
                    <input type="text" name="location" value="{{ old('location') }}"
                           placeholder="Ex: Rack A-01"
                           style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;">
                    @error('location')
                    <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                    Description
                </label>
                <textarea name="description" rows="4"
                          placeholder="Description de la ressource..."
                          style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;">{{ old('description') }}</textarea>
                @error('description')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" style="flex: 1; padding: 0.75rem 1.5rem; background-color: #3429d3; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Créer la Ressource</button>
                <a href="{{ route('resources.index') }}" style="flex: 1; padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center;">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection
