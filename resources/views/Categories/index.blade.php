@extends('layouts.app')

@section('title', 'Liste des Catégories')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/categories/index.css') }}">
@endsection

@section('content')
<div class="categories-container">
    <div class="categories-header">
        <h1>Gestion des Catégories</h1>

        {{-- REMPLACE la route par un lien direct --}}
        <a href="/categories/create" class="btn-new-category">
            + Nouvelle Catégorie
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($categories->isEmpty())
    <div class="empty-state">
        <h3>Aucune catégorie disponible</h3>
        <p>Créez votre première catégorie pour commencer.</p>
        {{-- REMPLACE la route par un lien direct --}}
        <a href="/categories/create">+ Créer une Catégorie</a>
    </div>
    @else
    <div class="categories-table-container">
        <table class="categories-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Ressources</th>
                    <th>Date de création</th>
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
                        <span class="resource-count">{{ $category->resources->count() }} ressource@if($category->resources->count() != 1)s@endif</span>
                    </td>
                    <td>{{ $category->created_at?->format('d/m/Y') ?? 'Date inconnue' }}</td>
                    <td class="actions-cell">
                        {{-- REMPLACE les routes par des liens directs --}}
                        <a href="/categories/{{ $category->id }}" class="btn-view">
                            Voir
                        </a>
                        <a href="/categories/{{ $category->id }}/edit" class="btn-edit">
                            Modifier
                        </a>
                        <form action="/categories/{{ $category->id }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/categories/index.js') }}"></script>
@endsection
