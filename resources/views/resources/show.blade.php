@extends('layouts.app') {{-- Utilise le layout principal --}}

@section('title', $resource->name) {{-- Titre de la page --}}

@section('styles')
<link rel="stylesheet" href="{{ asset('css/resources/show.css') }}"> {{-- CSS spécifique --}}
@endsection

@section('content')
<div class="resources-show">
    {{-- Section en-tête --}}
    <div class="page-header">
        <div class="header-info">
            {{-- Nom de la ressource --}}
            <h1>{{ $resource->name }}</h1>

            {{-- Métadonnées --}}
            <div class="resource-meta">
                {{-- ID --}}
                <span class="badge">ID: {{ $resource->id }}</span>

                {{-- Catégorie --}}
                <span class="category-badge">{{ $resource->category->name }}</span>

                {{-- Statut --}}
                <span class="status-badge {{ $resource->status }}">{{ $resource->status }}</span>

                {{-- Date de création avec vérification null --}}
                <span class="created-date">
                    Créée le: {{ $resource->created_at?->format('d/m/Y') ?? 'Date inconnue' }}
                </span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="header-actions">
            {{-- Modifier --}}
            <a href="{{ route('resources.edit', $resource) }}" class="btn edit">Modifier</a>

            {{-- CORRECTION : Utilisez une route qui existe --}}
            <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" class="btn reserve">Réserver</a>

            {{-- Retour --}}
            <a href="{{ route('resources.index') }}" class="btn secondary">Retour</a>
        </div>
    </div>

    {{-- Contenu principal --}}
    <div class="resource-content">
        {{-- Grille de spécifications --}}
        <div class="resource-grid">
            {{-- Carte spécifications --}}
            <div class="specs-card">
                <h2>Spécifications Techniques</h2>
                <div class="specs-list">
                    {{-- CPU --}}
                    <div class="spec-item">
                        <span class="spec-label">CPU:</span>
                        <span class="spec-value">{{ $resource->cpu }} cores</span>
                    </div>

                    {{-- RAM --}}
                    <div class="spec-item">
                        <span class="spec-label">RAM:</span>
                        <span class="spec-value">{{ $resource->ram }} GB</span>
                    </div>

                    {{-- Stockage --}}
                    <div class="spec-item">
                        <span class="spec-label">Stockage:</span>
                        <span class="spec-value">{{ $resource->storage }} GB</span>
                    </div>

                    {{-- Localisation --}}
                    <div class="spec-item">
                        <span class="spec-label">Localisation:</span>
                        <span class="spec-value">{{ $resource->location ?? 'Non spécifiée' }}</span>
                    </div>

                    {{-- Catégorie --}}
                    <div class="spec-item">
                        <span class="spec-label">Catégorie:</span>
                        <span class="spec-value">{{ $resource->category->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Carte description --}}
            <div class="description-card">
                <h2>Description</h2>
                <div class="description-content">
                    {{ $resource->description ?? 'Aucune description disponible.' }}
                </div>
            </div>
        </div>

        {{-- Section réservations --}}
        <div class="reservations-section">
            <h2>Réservations ({{ $resource->reservations->count() }})</h2>

            {{-- Si aucune réservation --}}
            @if($resource->reservations->isEmpty())
            <div class="empty-reservations">
                <p>Aucune réservation pour cette ressource</p>
                {{-- CORRECTION : Utilisez une route qui existe --}}
                <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" class="btn primary">
                    Faire une réservation
                </a>
            </div>
            @else
            {{-- Tableau des réservations --}}
            <div class="reservations-table">
                <table>
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Boucle sur les réservations --}}
                        @foreach($resource->reservations->sortByDesc('date_start') as $reservation)
                        <tr>
                            {{-- Utilisateur --}}
                            <td>{{ $reservation->user->name }}</td>

                            {{-- Date début --}}
                            <td>{{ $reservation->date_start?->format('d/m/Y H:i') ?? 'Non définie' }}</td>

                            {{-- Date fin --}}
                            <td>{{ $reservation->date_end?->format('d/m/Y H:i') ?? 'Non définie' }}</td>

                            {{-- Statut --}}
                            <td>
                                <span class="reservation-status {{ $reservation->status }}">
                                    {{ $reservation->status }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="reservation-actions">
                                <a href="#" class="btn small view">Voir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Section maintenance --}}
        <div class="maintenance-section">
            <h2>Historique de Maintenance</h2>

            {{-- Actions maintenance --}}
            <div class="maintenance-actions">
                {{-- Ajouter maintenance --}}
                <a href="{{ route('maintenance.create', $resource->id) }}" class="btn primary">
                    Ajouter une maintenance
                </a>
                {{-- Voir toutes --}}
                <a href="{{ route('maintenances.index') }}" class="btn secondary">
                    Voir toutes les maintenances
                </a>
            </div>

            {{-- Si aucune maintenance --}}
            @if($resource->maintenances->isEmpty())
            <p class="no-maintenance">Aucune maintenance enregistrée</p>
            @else
            {{-- Liste des maintenances --}}
            <div class="maintenance-list">
                @foreach($resource->maintenances->sortByDesc('date_start') as $maintenance)
                <div class="maintenance-item">
                    {{-- Dates --}}
                    <div class="maintenance-date">
                        {{ $maintenance->date_start?->format('d/m/Y') ?? 'Date indéfinie' }} -
                        {{ $maintenance->date_end?->format('d/m/Y') ?? 'Date indéfinie' }}
                    </div>
                    <div class="maintenance-desc">{{ $maintenance->description }}</div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Actions finales --}}
        <div class="resource-actions">
            {{-- Formulaire suppression --}}
            <form action="{{ route('resources.destroy', $resource) }}" method="POST"
                  onsubmit="return confirm('Supprimer cette ressource? Toutes les réservations seront annulées.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn delete">Supprimer la Ressource</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/resources/show.js') }}"></script> {{-- JS spécifique --}}
@endsection
