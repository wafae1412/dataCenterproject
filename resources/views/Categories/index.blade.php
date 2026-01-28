@extends('layouts.app')

@section('title', 'Catégories')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="main-content">
    <div class="dashboard-header">
        <div>
            <h1><i class="fas fa-tags"></i> Catégories</h1>
            <p class="subtitle">Gestion des catégories de ressources.</p>
        </div>
        @if(auth()->user()->role->name === 'Admin' || auth()->user()->role->name === 'Responsable')
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle Catégorie
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-section">
        <div class="table-container">
            <table class="table categories-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Nombre de Ressources</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>{{ $category->description ?? '-' }}</td>
                            <td>
                                <span class="status-badge status-active">
                                    {{ $category->resources_count ?? $category->resources->count() }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('categories.show', $category) }}" class="btn btn-info btn-small">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->user()->role->name === 'Admin')
                                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-small">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Aucune catégorie trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection