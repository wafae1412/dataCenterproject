@extends('layouts.app')

@section('title', $category->name)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/categories/show.css') }}">
@endsection

@section('content')
<div class="categories-show">
    <div class="page-header">
        <div class="header-info">
            <h1>{{ $category->name }}</h1>
            <div class="category-meta">
                <span class="badge">ID: {{ $category->id }}</span>
                <span class="created-date">Créée le: {{ $category->created_at->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="header-actions">
            <a href="{{ route('categories.edit', $category) }}" class="btn edit">Modifier</a>
            <a href="{{ route('categories.index') }}" class="btn secondary">Retour</a>
        </div>
    </div>

    <div class="category-content">
        <div class="resources-section">
            <h2>Ressources ({{ $category->resources->count() }})</h2>

            @if($category->resources->isEmpty())
            <div class="empty-resources">
                <p>Aucune ressource dans cette catégorie</p>
                <a href="{{ route('resources.create') }}" class="btn small primary">Ajouter une ressource</a>
            </div>
            @else
            <div class="resources-grid">
                @foreach($category->resources as $resource)
                <div class="resource-card">
                    <h3>{{ $resource->name }}</h3>
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
                    <div class="resource-status {{ $resource->status }}">
                        {{ ucfirst($resource->status) }}
                    </div>
                    <div class="resource-actions">
                        <a href="{{ route('resources.show', $resource) }}" class="btn small view">Voir</a>
                        <a href="{{ route('resources.edit', $resource) }}" class="btn small edit">Modifier</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="category-actions">
            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                  onsubmit="return confirm('Supprimer cette catégorie et toutes ses ressources?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn delete">Supprimer la Catégorie</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/categories/show.js') }}"></script>
@endsection
