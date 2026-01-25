@extends('layouts.app')

@section('title', 'Modifier la Catégorie')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/categories/edit.css') }}">
@endsection

@section('content')
<div class="categories-edit">
    <div class="page-header">
        <h1>Modifier la Catégorie: {{ $category->name }}</h1>
        <div class="header-actions">
            <a href="{{ route('categories.show', $category) }}" class="btn view">Voir</a>
            <a href="{{ route('categories.index') }}" class="btn secondary">Retour</a>
        </div>
    </div>

    <div class="form-container">
        <form action="{{ route('categories.update', $category) }}" method="POST" id="category-edit-form">
            @csrf @method('PUT')

            <div class="form-group">
                <label for="name">Nom de la catégorie *</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $category->name) }}"
                       required
                       placeholder="Nom de la catégorie">
                @error('name')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="resource-count">
                <h3>Ressources associées: {{ $category->resources->count() }}</h3>
                @if($category->resources->count() > 0)
                <ul class="resources-list">
                    @foreach($category->resources->take(5) as $resource)
                    <li>{{ $resource->name }}</li>
                    @endforeach
                    @if($category->resources->count() > 5)
                    <li>... et {{ $category->resources->count() - 5 }} autres</li>
                    @endif
                </ul>
                @else
                <p class="no-resources">Aucune ressource dans cette catégorie</p>
                @endif
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary">Mettre à jour</button>
                <a href="{{ route('categories.index') }}" class="btn cancel">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/categories/edit.js') }}"></script>
@endsection
