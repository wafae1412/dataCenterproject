@extends('layouts.app')

@section('title', 'Détails Maintenance')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/maintenance/show.css') }}">
@endsection

@section('content')
<div class="maintenance-show-container">
    <div class="page-header">
        <div class="header-content">
            <span class="status-badge status-{{ $maintenance->status }}">
                {{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}
            </span>
            <h1>{{ $maintenance->title }}</h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            @if(auth()->user()->role->name === 'Admin' || auth()->user()->role->name === 'Responsable')
                <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" onsubmit="return confirm('Confirmer la suppression définitive ?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="details-grid">
        <!-- Carte Info Ressource -->
        <div class="detail-card resource-card">
            <h3><i class="fas fa-server"></i> Ressource Concernée</h3>
            <div class="card-content">
                <p class="resource-name">{{ $maintenance->resource->name }}</p>
                <p class="resource-meta">
                    <span class="label">Catégorie:</span> {{ $maintenance->resource->category->name }}<br>
                    <span class="label">Type:</span> {{ $maintenance->resource->type }}
                </p>
                <a href="{{ route('resources.show', $maintenance->resource->id) }}" class="btn btn-small btn-info">
                    Voir la ressource
                </a>
            </div>
        </div>

        <!-- Carte Planning -->
        <div class="detail-card planning-card">
            <h3><i class="fas fa-calendar-alt"></i> Planification</h3>
            <div class="card-content">
                <div class="date-row">
                    <div class="date-item">
                        <span class="label">Début</span>
                        <span class="value">{{ $maintenance->start_date->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="arrow"><i class="fas fa-long-arrow-alt-right"></i></div>
                    <div class="date-item">
                        <span class="label">Fin prévue</span>
                        <span class="value">{{ $maintenance->end_date->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
                <div class="duration-info">
                    <i class="fas fa-hourglass-half"></i> Durée estimée : <strong>{{ $maintenance->estimated_duration ?? 'N/A' }} heures</strong>
                </div>
            </div>
        </div>

        <!-- Carte Technique -->
        <div class="detail-card technical-card">
            <h3><i class="fas fa-cogs"></i> Détails Techniques</h3>
            <div class="card-content">
                <div class="info-group">
                    <label>Type d'intervention</label>
                    <span class="type-badge type-{{ $maintenance->type }}">
                        {{ ucfirst($maintenance->type) }}
                    </span>
                </div>
                <div class="info-group">
                    <label>Description</label>
                    <p class="text-content">{{ $maintenance->description ?? 'Aucune description fournie.' }}</p>
                </div>
            </div>
        </div>

        <!-- Carte Notes -->
        @if($maintenance->notes)
        <div class="detail-card notes-card full-width">
            <h3><i class="fas fa-sticky-note"></i> Notes Internes</h3>
            <div class="card-content">
                <div class="notes-box">
                    {{ $maintenance->notes }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection