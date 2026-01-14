@extends('layouts.app')

@section('title', 'Créer une Catégorie')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/categories/create.css') }}">
@endsection

@section('content')
<div class="categories-create">
    <div class="page-header">
        <h1>Créer une Nouvelle Catégorie</h1>
        <a href="{{ route('categories.index') }}" class="btn secondary">Retour à la liste</a>
    </div>

    <div class="form-container">
        <form action="{{ route('categories.store') }}" method="POST" id="category-form">
            @csrf

            <div class="form-group">
                <label for="name">Nom de la catégorie *</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name') }}"
                       required
                       placeholder="Ex: Serveurs Physiques">
                @error('name')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary">Créer la Catégorie</button>
                <a href="{{ route('categories.index') }}" class="btn cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/categories/create.js') }}"></script>
@endsection
