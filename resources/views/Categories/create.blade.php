@extends('layouts.app') {{-- Layout principal --}}

@section('title', 'Créer une Catégorie') {{-- Titre de la page --}}

@section('styles')
<link rel="stylesheet" href="{{ asset('css/categories/create.css') }}"> {{-- CSS spécifique --}}
@endsection

@section('content')
<div class="categories-create">
    {{-- En-tête de la page --}}
    <div class="page-header">
        <h1>Créer une Nouvelle Catégorie</h1>
        {{-- Bouton retour --}}
        <a href="{{ route('categories.index') }}" class="btn secondary">Retour à la liste</a>
    </div>

    {{-- Conteneur du formulaire --}}
    <div class="form-container">
        {{-- Formulaire de création --}}
        <form action="{{ route('categories.store') }}" method="POST" id="category-form">
            @csrf {{-- Protection CSRF --}}

            {{-- Champ nom --}}
            <div class="form-group">
                <label for="name">Nom de la catégorie *</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       required
                       placeholder="Ex: Serveurs Physiques">
                {{-- Affichage des erreurs --}}
                @error('name')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            {{-- Actions du formulaire --}}
            <div class="form-actions">
                {{-- Bouton de création --}}
                <button type="submit" class="btn primary">Créer la Catégorie</button>
                {{-- Bouton d'annulation --}}
                <a href="{{ route('categories.index') }}" class="btn cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/categories/create.js') }}"></script> {{-- JavaScript spécifique --}}
@endsection
