@extends('layouts.app')

@section('title', 'Détails Ressource')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="main-content">
    <div class="dashboard-header">
        <div>
            <h1><i class="fas fa-server"></i> {{ $resource->name }}</h1>
            <p class="subtitle">Détails techniques et état de la ressource.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('resources.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            @if(auth()->user()->role->name === 'Admin')
                <a href="{{ route('resources.edit', $resource) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <form action="{{ route('resources.destroy', $resource) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-info-circle"></i> Informations Générales</h2>
            <span class="status-badge status-{{ $resource->status }}">
                {{ ucfirst($resource->status) }}
            </span>
        </div>
        
        <div class="detail-grid">
            <div class="detail-item">
                <label>Catégorie</label>
                <p>{{ $resource->category->name ?? 'Non catégorisé' }}</p>
            </div>
            <div class="detail-item">
                <label>Localisation</label>
                <p>{{ $resource->location ?? 'Non spécifiée' }}</p>
            </div>
            <div class="detail-item">
                <label>Description</label>
                <p>{{ $resource->description ?? 'Aucune description.' }}</p>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-microchip"></i> Spécifications Techniques</h2>
        </div>
        <div class="spec-grid">
            <div class="spec-item">
                <span class="spec-label">CPU (Cores)</span>
                <span class="spec-value">{{ $resource->cpu }}</span>
            </div>
            <div class="spec-item">
                <span class="spec-label">RAM (GB)</span>
                <span class="spec-value">{{ $resource->ram }}</span>
            </div>
            <div class="spec-item">
                <span class="spec-label">Stockage (GB)</span>
                <span class="spec-value">{{ $resource->storage }}</span>
            </div>
        </div>
    </div>

    @if($resource->maintenances->isNotEmpty())
    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-tools"></i> Historique de Maintenance</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resource->maintenances as $maintenance)
                        <tr>
                            <td>{{ $maintenance->title }}</td>
                            <td>{{ ucfirst($maintenance->type) }}</td>
                            <td>{{ $maintenance->start_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="status-badge status-{{ $maintenance->status }}">
                                    {{ ucfirst($maintenance->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection