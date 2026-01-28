@extends('layouts.app')

@section('title', 'Détails Catégorie')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="main-content">
    <div class="dashboard-header">
        <div>
            <h1><i class="fas fa-tag"></i> {{ $category->name }}</h1>
            <p class="subtitle">Détails de la catégorie et ressources associées.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            @if(auth()->user()->role->name === 'Admin')
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            @endif
        </div>
    </div>

    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-info-circle"></i> Informations</h2>
        </div>
        <div style="padding: 1rem;">
            <p><strong>Description :</strong> {{ $category->description ?? 'Aucune description.' }}</p>
            <p><strong>Nombre de ressources :</strong> {{ $category->resources->count() }}</p>
        </div>
    </div>

    <div class="dashboard-section">
        <div class="section-header">
            <h2><i class="fas fa-server"></i> Ressources dans cette catégorie</h2>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($category->resources as $resource)
                        <tr>
                            <td>{{ $resource->name }}</td>
                            <td>
                                <span class="status-badge status-{{ $resource->status }}">
                                    {{ ucfirst($resource->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('resources.show', $resource) }}" class="btn btn-info btn-small">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Aucune ressource dans cette catégorie.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection