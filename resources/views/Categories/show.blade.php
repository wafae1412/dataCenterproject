@extends('layouts.app')

@section('content')

<div style="max-width: 1200px; margin: 2rem auto; padding: 2rem;">
    <!-- En-tête -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
        <div>
            <h1 style="color: #0a2a43; margin: 0 0 1rem 0;">{{ $category->name }}</h1>
            
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <span style="display: inline-block; background-color: #3429d3; color: white; padding: 0.5rem 1rem; border-radius: 4px; font-size: 0.85rem;">
                    ID: {{ $category->id }}
                </span>
                <span style="display: inline-block; color: #6b7280; padding: 0.5rem 1rem; font-size: 0.85rem;">
                    Créée le: {{ $category->created_at?->format('d/m/Y') ?? 'Date inconnue' }}
                </span>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('categories.edit', $category->id) }}" style="padding: 0.5rem 1rem; background-color: #f59e0b; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Modifier</a>
            <a href="{{ route('categories.index') }}" style="padding: 0.5rem 1rem; background-color: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">Retour</a>
        </div>
    </div>

    <!-- Ressources -->
    <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #0a2a43; margin-top: 0;">Ressources ({{ $category->resources->count() }})</h2>

        @if($category->resources->isEmpty())
            <div style="text-align: center; padding: 2rem;">
                <p style="color: #6b7280; margin-bottom: 1rem;">Aucune ressource dans cette catégorie</p>
                <a href="{{ route('resources.create') }}" style="display: inline-block; padding: 0.5rem 1rem; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px;">Ajouter une ressource</a>
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                @foreach($category->resources as $resource)
                    <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); padding: 1.5rem; border-top: 4px solid #3429d3;">
                        <h3 style="margin: 0 0 1rem 0; color: #0a2a43;">{{ $resource->name }}</h3>

                        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1rem; padding: 1rem 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                                <span style="font-weight: 600; color: #6b7280; min-width: 50px;">CPU:</span>
                                <span>{{ $resource->cpu }} cores</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                                <span style="font-weight: 600; color: #6b7280; min-width: 50px;">RAM:</span>
                                <span>{{ $resource->ram }} GB</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                                <span style="font-weight: 600; color: #6b7280; min-width: 50px;">Stockage:</span>
                                <span>{{ $resource->storage }} GB</span>
                            </div>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;
                                @if($resource->status == 'available') background-color: #d1fae5; color: #065f46;
                                @elseif($resource->status == 'reserved') background-color: #fecaca; color: #7f1d1d;
                                @elseif($resource->status == 'maintenance') background-color: #fef3c7; color: #92400e;
                                @else background-color: #e5e7eb; color: #374151;
                                @endif
                            ">
                                {{ ucfirst($resource->status) }}
                            </span>
                        </div>

                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('resources.show', $resource->id) }}" style="flex: 1; padding: 0.5rem 1rem; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center; font-size: 0.85rem;">Voir</a>
                            <a href="{{ route('resources.edit', $resource->id) }}" style="flex: 1; padding: 0.5rem 1rem; background-color: #f59e0b; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; text-align: center; font-size: 0.85rem;">Modifier</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Actions sur la catégorie -->
    <div style="background: white; border-radius: 10px; padding: 2rem; margin-top: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #0a2a43; margin-top: 0;">Actions</h2>
        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
              onsubmit="return confirm('Supprimer cette catégorie et toutes ses ressources?')">
            @csrf @method('DELETE')
            <button type="submit" style="padding: 0.75rem 1.5rem; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Supprimer la Catégorie</button>
        </form>
    </div>
</div>

@endsection
