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

        {{-- Bouton ajouter --}}
        <a href="{{ route('resources.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Ressource
        </a>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filtres --}}
    <div class="filters-section">
        <h3><i class="fas fa-filter"></i> Filtres</h3>
        <form method="GET" action="{{ route('resources.index') }}" id="filter-form">
            <div class="filters-grid">
                {{-- Catégorie --}}
                <div class="filter-field">
                    <label><i class="fas fa-tag"></i> Catégorie</label>
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
                    <label><i class="fas fa-info-circle"></i> Statut</label>
                    <select name="status" id="filter-status">
                        <option value="">Tous</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Réservé</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Désactivé</option>
                    </select>
                </div>

                {{-- Recherche --}}
                <div class="filter-field">
                    <label><i class="fas fa-search"></i> Recherche</label>
                    <input type="text" name="search" id="filter-search"
                           placeholder="Nom..." value="{{ request('search') }}">
                </div>

                {{-- Boutons --}}
                <div class="filter-field">
                    <label>&nbsp;</label>
                    <div class="filter-buttons">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-check"></i> Filtrer
                        </button>
                        <a href="{{ route('resources.index') }}" class="action-btn">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div class="stats-section">
        <h3><i class="fas fa-chart-bar"></i> Statistiques</h3>
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
                        data-status="{{ $resource->status }}"
                        data-resource-id="{{ $resource->id }}"
                        onclick="reserveResource({{ $resource->id }}, '{{ $resource->name }}')">
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
                        <td onclick="event.stopPropagation();">
                            <div class="resource-actions">
                                {{-- Voir --}}
                                <a href="{{ route('resources.show', $resource->id) }}"
                                   class="action-btn-view"
                                   title="Voir les détails">
                                    <i class="fas fa-eye"></i> Voir
                                </a>

                                {{-- Éditer --}}
                                <a href="{{ route('resources.edit', $resource->id) }}"
                                   class="action-btn-edit"
                                   title="Modifier">
                                    <i class="fas fa-edit"></i> Éditer
                                </a>

                                {{-- Réserver --}}
                                @if($resource->status == 'available')
                                <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}"
                                   class="action-btn-reserve"
                                   data-resource-id="{{ $resource->id }}"
                                   data-resource-name="{{ $resource->name }}"
                                   title="Réserver">
                                    <i class="fas fa-calendar-check"></i> Réserver
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
<script>
function reserveResource(resourceId, resourceName) {
    // Redirect to reservation form with pre-filled resource
    window.location.href = '{{ route("reservations.create") }}?resource_id=' + resourceId;
}
</script>
<script src="{{ asset('js/resources/index.js') }}"></script>
@endsection
