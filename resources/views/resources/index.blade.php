@extends('layouts.app') {{-- layout principal --}}

@section('title', 'Ressources')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/resources/index.css') }}">
@endsection

@section('content')
<div class="resources-index">
    {{-- Header --}}
    <div class="page-header">
        <h1>Ressources DataCenter</h1>

        {{-- Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        {{-- Bouton ajouter --}}
        <a href="{{ route('resources.create') }}" class="btn btn-primary">
            + Nouvelle Ressource
        </a>
    </div>

    {{-- Filtres --}}
    <div class="filters-section">
        <h3>Filtres</h3>
        <form method="GET" action="{{ route('resources.index') }}" id="filter-form">
            <div class="filters-grid">
                {{-- Catégorie --}}
                <div class="filter-field">
                    <label>Catégorie</label>
                    <select name="category_id" id="filter-category">
                        <option value="">Toutes</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Statut --}}
                <div class="filter-field">
                    <label>Statut</label>
                    <select name="status" id="filter-status">
                        <option value="">Tous</option>
                        <option value="available">Disponible</option>
                        <option value="reserved">Réservé</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="disabled">Désactivé</option>
                    </select>
                </div>

                {{-- Recherche --}}
                <div class="filter-field">
                    <label>Recherche</label>
                    <input type="text" name="search" id="filter-search"
                           placeholder="Nom..." value="{{ request('search') }}">
                </div>

                {{-- Boutons --}}
                <div class="filter-field">
                    <label>&nbsp;</label>
                    <div class="filter-buttons">
                        <button type="submit" class="btn-primary">Filtrer</button>
                        <a href="{{ route('resources.index') }}" class="action-btn">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div class="stats-section">
        <h3>Stats</h3>
        <div class="stats-grid">
            {{-- Total --}}
            <div class="stat-card stat-total">
                <span class="stat-number">{{ $resources->count() }}</span>
                <span class="stat-label">Total</span>
            </div>

            {{-- Disponibles --}}
            <div class="stat-card stat-available">
                <span class="stat-number">{{ $resources->where('status', 'available')->count() }}</span>
                <span class="stat-label">Disponibles</span>
            </div>

            {{-- Réservées --}}
            <div class="stat-card stat-reserved">
                <span class="stat-number">{{ $resources->where('status', 'reserved')->count() }}</span>
                <span class="stat-label">Réservées</span>
            </div>
        </div>
    </div>

    {{-- Table --}}
    @if($resources->isEmpty())
        {{-- Aucune ressource --}}
        <div class="empty-state">
            <h3>Aucune ressource</h3>
            <p>Créez votre première ressource</p>
            <a href="{{ route('resources.create') }}" class="btn btn-primary">
                Créer ressource
            </a>
        </div>
    @else
        {{-- Liste des ressources --}}
        <div class="resources-table-container">
            <table class="resources-table" id="resources-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Spécifications</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Boucle ressources --}}
                    @foreach($resources as $resource)
                    <tr class="resource-row"
                        data-id="{{ $resource->id }}"
                        data-category="{{ $resource->category_id }}"
                        data-status="{{ $resource->status }}">
                        <td>{{ $resource->id }}</td>

                        {{-- Nom --}}
                        <td>
                            <div class="resource-name">{{ $resource->name }}</div>
                            <div class="resource-category">{{ $resource->category->name ?? 'N/A' }}</div>
                        </td>

                        {{-- Catégorie --}}
                        <td>{{ $resource->category->name ?? 'N/A' }}</td>

                        {{-- Spécifications --}}
                        <td>
                            <div class="resource-specs">
                                <div class="spec-item">
                                    <span class="spec-label">CPU:</span>
                                    <span class="spec-value">{{ $resource->cpu }} cores</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">RAM:</span>
                                    <span class="spec-value">{{ $resource->ram }} GB</span>
                                </div>
                                <div class="spec-item">
                                    <span class="spec-label">Stockage:</span>
                                    <span class="spec-value">{{ $resource->storage }} GB</span>
                                </div>
                            </div>
                        </td>

                        {{-- Statut --}}
                        <td>
                            <span class="status-badge status-{{ $resource->status }}">
                                {{ $resource->status }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="resource-actions">
                                {{-- Voir --}}
                                <a href="{{ route('resources.show', $resource->id) }}"
                                   class="action-btn action-btn-view">
                                    Voir
                                </a>

                                {{-- Éditer --}}
                                <a href="{{ route('resources.edit', $resource->id) }}"
                                   class="action-btn action-btn-edit">
                                    Éditer
                                </a>

                                {{-- Réserver --}}
                                @if($resource->status == 'available')
                                <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}"
                                   class="action-btn action-btn-reserve"
                                   data-resource-id="{{ $resource->id }}"
                                   data-resource-name="{{ $resource->name }}">
                                    Réserver
                                </a>
                                @endif
                            </div>
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
<script src="{{ asset('js/resources/index.js') }}"></script>
@endsection
