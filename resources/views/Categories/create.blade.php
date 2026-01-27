@extends('layouts.app')

@section('content')

<div style="max-width: 800px; margin: 2rem auto; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
        <h1 style="color: #0a2a43; margin: 0;">Créer une Nouvelle Catégorie</h1>
        <a href="{{ route('categories.index') }}" style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Retour à la liste</a>
    </div>

    <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">
                    Nom de la catégorie <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="Ex: Serveurs Physiques"
                       style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;">
                @error('name')
                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" style="flex: 1; padding: 0.75rem 1.5rem; background-color: #3429d3; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Créer la Catégorie</button>
                <a href="{{ route('categories.index') }}" style="flex: 1; padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center;">Annuler</a>
            </div>
        </form>
    </div>
</div>

@endsection
