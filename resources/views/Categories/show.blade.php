@extends('layouts.app') {{-- Layout principal --}}

@section('title', $category->name) {{-- Titre de la page --}}

@section('styles')
<link rel="stylesheet" href="{{ asset('css/categories/show.css') }}"> {{-- CSS spécifique --}}
@endsection

@section('content')
<div class="categories-show">
    {{-- En-tête de la page --}}
    <div class="page-header">
        <div class="header-info">
            <h1>{{ $category->name }}</h1>
            <div class="category-meta">
                {{-- ID de la catégorie --}}
                <span class="badge">ID: {{ $category->id }}</span>

                {{-- Date de création avec vérification null --}}
                <span class="created-date">Créée le: {{ $category->created_at?->format('d/m/Y') ?? 'Date inconnue' }}</span>
            </div>
        </div>

        {{-- Boutons d'action --}}
        <div class="header-actions">
            {{-- Modifier la catégorie (LIEN DIRECT) --}}
            <a href="/categories/{{ $category->id }}/edit" class="btn edit">Modifier</a>

            {{-- Retour à la liste (LIEN DIRECT) --}}
            <a href="/categories" class="btn secondary">Retour</a>
        </div>
    </div>

    {{-- Contenu principal --}}
    <div class="category-content">
        {{-- Section des ressources --}}
        <div class="resources-section">
            <h2>Ressources ({{ $category->resources->count() }})</h2>

            {{-- Si aucune ressource --}}
            @if($category->resources->isEmpty())
            <div class="empty-resources">
                <p>Aucune ressource dans cette catégorie</p>
                {{-- Ajouter une ressource (LIEN DIRECT) --}}
                <a href="/resources/create" class="btn small primary">Ajouter une ressource</a>
            </div>
            @else
            {{-- Grille des ressources --}}
            <div class="resources-grid">
                {{-- Boucle sur les ressources --}}
                @foreach($category->resources as $resource)
                <div class="resource-card">
                    {{-- Nom de la ressource --}}
                    <h3>{{ $resource->name }}</h3>

                    {{-- Spécifications --}}
                    <div class="resource-specs">
                        <div class="spec">
                            <span class="spec-label">CPU:</span>
                            <span class="spec-value">{{ $resource->cpu }} cores</span>
                        </div>
                        <div class="spec">
                            <span class="spec-label">RAM:</span>
                            <span class="spec-value">{{ $resource->ram }} GB</span>
                        </div>
                        <div class="spec">
                            <span class="spec-label">Stockage:</span>
                            <span class="spec-value">{{ $resource->storage }} GB</span>
                        </div>
                    </div>

                    {{-- Statut de la ressource --}}
                    <div class="resource-status {{ $resource->status }}">
                        {{ ucfirst($resource->status) }}
                    </div>

                    {{-- Actions sur la ressource (LIENS DIRECTS) --}}
                    <div class="resource-actions">
                        <a href="/resources/{{ $resource->id }}" class="btn small view">Voir</a>
                        <a href="/resources/{{ $resource->id }}/edit" class="btn small edit">Modifier</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Actions sur la catégorie --}}
        <div class="category-actions">
            {{-- Formulaire de suppression (LIEN DIRECT) --}}
            <form action="/categories/{{ $category->id }}" method="POST"
                  onsubmit="return confirm('Supprimer cette catégorie et toutes ses ressources?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn delete">Supprimer la Catégorie</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/categories/show.js') }}"></script> {{-- JavaScript spécifique --}}
@endsection
