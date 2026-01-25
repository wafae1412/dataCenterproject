@extends('layouts.app')

@section('title', 'Liste des Maintenances')

@section('content')
<div class="container">
    <h2>Liste des Maintenances</h2>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- DEBUG --}}
    <div class="alert alert-info">
        <strong>Total:</strong> {{ $maintenances->count() }} maintenance(s)
    </div>

    <a href="{{ route('maintenance.create') }}" class="btn btn-primary mb-3">
        + Nouvelle maintenance
    </a>

    @if($maintenances->isEmpty())
        <div class="alert alert-warning">
            <p>Aucune maintenance planifiée.</p>
            <a href="{{ route('maintenance.create') }}" class="btn btn-sm btn-success">
                Créer la première maintenance
            </a>
        </div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Ressource</th>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenances as $maintenance)
                <tr>
                    <td>{{ $maintenance->id }}</td>
                    <td>
                        @if($maintenance->resource)
                            <strong>{{ $maintenance->resource->name }}</strong>
                            <br>
                            <small class="text-muted">
                                ID: {{ $maintenance->resource_id }}
                            </small>
                        @else
                            <span class="text-danger">
                                Ressource #{{ $maintenance->resource_id }} (supprimée?)
                            </span>
                        @endif
                    </td>
                    <td>{{ $maintenance->title ?? '---' }}</td>
                    <td>
                        <span class="badge bg-info">
                            {{ $maintenance->type }}
                        </span>
                    </td>
                    <td>
                        {{ $maintenance->start_date->format('d/m/Y H:i') }}
                    </td>
                    <td>
                        @if($maintenance->end_date)
                            {{ $maintenance->end_date->format('d/m/Y H:i') }}
                        @else
                            <span class="text-muted">---</span>
                        @endif
                    </td>
                    <td>
                       <span class="badge bg-{{
                        $maintenance->status == 'completed' ? 'success' :
                        ($maintenance->status == 'in_progress' ? 'warning' :
                        ($maintenance->status == 'scheduled' ? 'primary' : 'secondary'))
                      }}">
                     {{
                        $maintenance->status == 'completed' ? 'Terminé' :
                        ($maintenance->status == 'in_progress' ? 'En cours' :
                        ($maintenance->status == 'scheduled' ? 'Planifié' : 'Annulé'))
                    }}
                     </span>
                   </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('resources.index') }}" class="btn btn-secondary mt-3">
        ← Retour aux ressources
    </a>
</div>
@endsection
