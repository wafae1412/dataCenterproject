@extends('layouts.app')

@section('title', $resource->name ?? 'Ressource')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/resources/show.css') }}">
@endsection

@section('content')
<div class="resources-show">
    {{-- En-tête --}}
    <div class="page-header">
        <div class="header-info">
            <h1>{{ $resource->name ?? 'Ressource sans nom' }}</h1>

            <div class="resource-meta">
                <span class="badge">ID: {{ $resource->id ?? 'N/A' }}</span>

                {{-- Catégorie avec vérification --}}
                <span class="category-badge">
                    {{ $resource->category->name ?? 'Non catégorisé' }}
                </span>

                {{-- Statut --}}
                <span class="status-badge {{ $resource->status ?? 'unknown' }}">
                    {{ $resource->status ?? 'Inconnu' }}
                </span>

                <span class="created-date">
                    Créée le: {{ $resource->created_at?->format('d/m/Y') ?? 'Date inconnue' }}
                </span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="header-actions">
            <a href="{{ route('resources.edit', $resource) }}" class="btn edit">
                Modifier
            </a>

            {{-- Réserver seulement si disponible --}}
            @if(($resource->status ?? '') == 'available')
                <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}"
                   class="btn reserve">
                    Réserver
                </a>
            @endif

            <a href="{{ route('resources.index') }}" class="btn secondary">
                Retour
            </a>
        </div>
    </div>

    {{-- Contenu principal --}}
    <div class="resource-content">
        {{-- Spécifications --}}
        <div class="resource-grid">
            <div class="specs-card">
                <h2>Spécifications Techniques</h2>
                <div class="specs-list">
                    <div class="spec-item">
                        <span class="spec-label">CPU:</span>
                        <span class="spec-value">{{ $resource->cpu ?? 'N/A' }} cores</span>
                    </div>

                    <div class="spec-item">
                        <span class="spec-label">RAM:</span>
                        <span class="spec-value">{{ $resource->ram ?? 'N/A' }} GB</span>
                    </div>

                    <div class="spec-item">
                        <span class="spec-label">Stockage:</span>
                        <span class="spec-value">{{ $resource->storage ?? 'N/A' }} GB</span>
                    </div>

                    <div class="spec-item">
                        <span class="spec-label">Localisation:</span>
                        <span class="spec-value">{{ $resource->location ?? 'Non spécifiée' }}</span>
                    </div>

                    <div class="spec-item">
                        <span class="spec-label">Catégorie:</span>
                        <span class="spec-value">{{ $resource->category->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="description-card">
                <h2>Description</h2>
                <div class="description-content">
                    {{ $resource->description ?? 'Aucune description disponible.' }}
                </div>
            </div>
        </div>

        {{-- Réservations --}}
        <div class="reservations-section">
            <h2>Réservations
                @if(isset($resource->reservations))
                    ({{ $resource->reservations->count() }})
                @endif
            </h2>

            {{-- Vérifier si des réservations existent --}}
            @if(!isset($resource->reservations) || $resource->reservations->isEmpty())
                <div class="empty-reservations">
                    <p>Aucune réservation pour cette ressource</p>

                    @if(($resource->status ?? '') == 'available')
                        <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}"
                           class="btn primary">
                            Faire une réservation
                        </a>
                    @endif
                </div>
            @else
                {{-- Afficher le tableau des réservations --}}
                <div class="reservations-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Boucle sécurisée --}}
                            @foreach($resource->reservations->sortByDesc('date_start') as $reservation)
                            <tr>
                                <td>{{ $reservation->user->name ?? 'Utilisateur inconnu' }}</td>
                                <td>{{ $reservation->date_start?->format('d/m/Y H:i') ?? 'Non définie' }}</td>
                                <td>{{ $reservation->date_end?->format('d/m/Y H:i') ?? 'Non définie' }}</td>
                                <td>
                                    <span class="reservation-status {{ $reservation->status ?? 'unknown' }}">
                                        {{ $reservation->status ?? 'Inconnu' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- MAINTENANCE - SECTION CRITIQUE --}}
        <div class="maintenance-section">
            <h2>Maintenance</h2>

            {{-- Boutons d'action --}}
            <div class="maintenance-actions">
                {{-- Bouton pour planifier maintenance --}}
                @if(auth()->check())
                    <a href="{{ route('maintenance.create', $resource) }}" class="btn primary">
                        Planifier une maintenance
                    </a>
                @endif

                {{-- Bouton pour voir l'historique --}}
                <a href="{{ route('maintenances.index') }}" class="btn secondary">
                    Voir l'historique
                </a>
            </div>

            {{-- Historique des maintenances --}}
            <h3>Historique des maintenances</h3>

            {{-- Vérifier si des maintenances existent --}}
            @if(isset($resource->maintenances) && $resource->maintenances && $resource->maintenances->isNotEmpty())
                <div class="maintenance-list">
                    @foreach($resource->maintenances->sortByDesc('date_start') as $maintenance)
                    <div class="maintenance-item">
                        <div class="maintenance-date">
                            {{ $maintenance->date_start?->format('d/m/Y') ?? 'Date inconnue' }} -
                            {{ $maintenance->date_end?->format('d/m/Y') ?? 'Date inconnue' }}
                        </div>
                        <div class="maintenance-desc">
                            {{ $maintenance->description ?? 'Pas de description' }}
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="no-maintenance">Aucune maintenance enregistrée.</p>
            @endif
        </div>

        {{-- Actions de suppression --}}
        <div class="resource-actions">
            <form action="{{ route('resources.destroy', $resource) }}" method="POST"
                  onsubmit="return confirm('Supprimer cette ressource ? Toutes les réservations seront annulées.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn delete">
                    Supprimer la ressource
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/resources/show.js') }}"></script>
@endsection
