@extends('layouts.app')

@section('title', 'Liste des Catégories')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/categories/index.css') }}">
@endsection

@section('content')
<div class="categories-container">
    <div class="categories-header">
        <h1>Gestion des Catégories</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <a href="{{ route('categories.create') }}" class="btn-new-category">
            + Nouvelle Catégorie
        </a>
    </div>

    @if($categories->isEmpty())
    <div class="empty-state">
        <p>Aucune catégorie disponible.</p>
        <a href="{{ route('categories.create') }}">Créer votre première catégorie</a>
    </div>
    @else
    <table class="categories-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Ressources</th>
                <th>Date création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr data-category-id="{{ $category->id }}">
                <td>{{ $category->id }}</td>
                <td>
                    <strong>{{ $category->name }}</strong>
                </td>
                <td>
                    <span class="resource-count">{{ $category->resources->count() }} ressources</span>
                </td>
                <td>{{ $category->created_at->format('d/m/Y') }}</td>
                <td class="actions-cell">
                    <a href="{{ route('categories.show', $category) }}" class="btn-view">
                        Voir
                    </a>
                    <a href="{{ route('categories.edit', $category) }}" class="btn-edit">
                        Modifier
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="delete-form">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete">
                            Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/categories/index.js') }}"></script>
@endsection
