@extends('layouts.app')

@section('title', 'Ressources')

@section('content')
<div class="resources-index">
    {{-- Debug section --}}
    <div>
        <strong>Debug Filtres:</strong><br>
        category_id: {{ request('category_id') ?: 'null' }}<br>
        status: {{ request('status') ?: 'null' }}<br>
        search: {{ request('search') ?: 'null' }}<br>
        Total ressources affichees: {{ $resources->count() }}
    </div>

    {{-- Header --}}
    <div>
        <h1>Ressources DataCenter</h1>

        @if(session('success'))
            <div>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div>
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('resources.create') }}">
            + Nouvelle Ressource
        </a>
    </div>

    {{-- Filtres --}}
    <div>
        <h3>Filtres</h3>
        <form method="GET" action="{{ route('resources.index') }}" id="filter-form">
            <div>
                {{-- Catégorie --}}
                <div>
                    <label>Catégorie</label>
                    <select name="category_id" id="filter-category">
                        <option value="">Toutes les categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Statut --}}
                <div>
                    <label>Statut</label>
                    <select name="status" id="filter-status">
                        <option value="">Tous les statuts</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                            Disponible
                        </option>
                        <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>
                            Reserve
                        </option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                            Maintenance
                        </option>
                        <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>
                            Desactive
                        </option>
                    </select>
                </div>

                {{-- Recherche --}}
                <div>
                    <label>Recherche</label>
                    <input type="text" name="search" id="filter-search"
                           value="{{ request('search') }}" placeholder="Nom...">
                </div>

                {{-- Boutons --}}
                <div>
                    <button type="submit" id="filter-button">Filtrer</button>
                    <a href="{{ route('resources.index') }}">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Stats --}}
    <div>
        <h3>Stats</h3>
        <div>
            <div>
                <span>{{ $resources->count() }}</span>
                <span>Total</span>
            </div>
            <div>
                <span>{{ $resources->where('status', 'available')->count() }}</span>
                <span>Disponibles</span>
            </div>
            <div>
                <span>{{ $resources->where('status', 'reserved')->count() }}</span>
                <span>Reservees</span>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    @if($resources->isEmpty())
        <div>
            <h3>Aucune ressource</h3>
            <p>Creez votre premiere ressource</p>
            <a href="{{ route('resources.create') }}">Creer ressource</a>
        </div>
    @else
        <div>
            <table id="resources-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Categorie</th>
                        <th>Specifications</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resources as $resource)
                    <tr data-id="{{ $resource->id }}"
                        data-category="{{ $resource->category_id }}"
                        data-status="{{ $resource->status }}">
                        <td>{{ $resource->id }}</td>
                        <td>
                            <div>{{ $resource->name }}</div>
                            <div>{{ $resource->category->name ?? 'N/A' }}</div>
                        </td>
                        <td>{{ $resource->category->name ?? 'N/A' }}</td>
                        <td>
                            CPU: {{ $resource->cpu }} cores<br>
                            RAM: {{ $resource->ram }} GB<br>
                            Stockage: {{ $resource->storage }} GB
                        </td>
                        <td>
                            {{ $resource->status }}
                        </td>
                        <td class="resource-actions">
                            <a href="{{ route('resources.show', $resource->id) }}">Voir</a>
                            <a href="{{ route('resources.edit', $resource->id) }}">Editer</a>
                            @if($resource->status == 'available')
                            <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}">Reserver</a>
                            @endif
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
