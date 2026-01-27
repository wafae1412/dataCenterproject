@extends('layouts.app')

@section('content')

<div style="max-width: 1200px; margin: 2rem auto; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
        <h1 style="color: #0a2a43; margin: 0;">Gestion des Catégories</h1>
        @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
            <a href="{{ route('categories.create') }}" style="padding: 0.75rem 1.5rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">+ Nouvelle Catégorie</a>
        @endif
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; margin-bottom: 1.5rem; border-radius: 6px; border-left: 4px solid #10b981;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #fee2e2; color: #7f1d1d; padding: 1rem; margin-bottom: 1.5rem; border-radius: 6px; border-left: 4px solid #ef4444;">
            {{ session('error') }}
        </div>
    @endif

    @if($categories->isEmpty())
        <div style="background: white; border-radius: 10px; padding: 3rem 2rem; text-align: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <h3 style="color: #0a2a43;">Aucune catégorie disponible.</h3>
            <p style="color: #6b7280;">Créez votre première catégorie pour commencer.</p>
            <a href="{{ route('categories.create') }}" style="display: inline-block; padding: 0.75rem 1.5rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 1rem;">Créer votre première catégorie</a>
        </div>
    @else
        <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #0a2a43; color: white;">
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">ID</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Nom</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Ressources</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Date création</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 0.75rem 1rem;">{{ $category->id }}</td>
                            <td style="padding: 0.75rem 1rem;">
                                <strong>{{ $category->name }}</strong>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <span style="display: inline-block; background-color: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.85rem;">{{ $category->resources->count() }} ressources</span>
                            </td>
                            <td style="padding: 0.75rem 1rem;">{{ $category->created_at?->format('d/m/Y') ?? 'Date inconnue' }}</td>
                            <td style="padding: 0.75rem 1rem;">
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="{{ route('categories.show', $category->id) }}" style="padding: 0.5rem 1rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-size: 0.85rem;">Voir</a>
                                    <a href="{{ route('categories.edit', $category->id) }}" style="padding: 0.5rem 1rem; background-color: #f59e0b; color: white; text-decoration: none; border-radius: 6px; font-size: 0.85rem;">Modifier</a>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="padding: 0.5rem 1rem; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-size: 0.85rem; cursor: pointer;" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                                    </form>
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
