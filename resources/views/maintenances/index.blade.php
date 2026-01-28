@extends('layouts.app')

@section('title', 'Gestion des Maintenances')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="main-content">
    <div class="dashboard-header">
        <div>
            <h1><i class="fas fa-wrench"></i> Gestion des Maintenances</h1>
            <p class="subtitle">Suivi des opérations de maintenance planifiées et en cours.</p>
        </div>
        <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Planifier une maintenance
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    
    <div class="maintenance-full-width-container">
        <div class="card maintenance-card">
            <div class="card-header maintenance-header">
                <h3><i class="fas fa-list"></i> Liste des Maintenances</h3>
            </div>
            <div class="card-body maintenance-body">
                @if($maintenances->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-tools"></i>
                        <p>Aucune maintenance planifiée pour le moment.</p>
                    </div>
                @else
                    <div class="table-container">
                        <table class="table maintenance-full-width-table">
                            <thead>
                                <tr>
                                    <th>Ressource</th>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Début</th>
                                    <th>Fin</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenances as $maintenance)
                                    <tr>
                                        <td>{{ $maintenance->resource->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('maintenances.show', $maintenance) }}" class="link-primary">
                                                {{ $maintenance->title }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $maintenance->type }}">{{ ucfirst($maintenance->type) }}</span>
                                        </td>
                                        <td>{{ $maintenance->start_date->format('d/m/Y H:i') }}</td>
                                        <td>{{ $maintenance->end_date->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $maintenance->status }}">{{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('maintenances.show', $maintenance) }}" class="btn btn-info btn-small" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-warning btn-small" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette maintenance ?');" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-small" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
        </div>
    </div>
</div>

<style>
    
    .badge {
        padding: 0.3em 0.6em;
        border-radius: 0.25rem;
        font-size: 0.8em;
        font-weight: 600;
        color: #fff;
    }
    .badge-preventive { background-color: #3498db; }
    .badge-corrective { background-color: #f39c12; }
    .badge-emergency { background-color: #e74c3c; }
    .badge-upgrade { background-color: #27ae60; }

    .link-primary {
        color: #222527;
        text-decoration: none;
        
    }
    .link-primary:hover {
        color: #2980b9;
        text-decoration: underline;
    }

    
    .maintenance-full-width-container {
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 0;
    }
    
  
    .maintenance-card {
        width: 100%;
        max-width: 100%;
        margin: 0;
        border-radius: 8px;
        border: 1px solid #e0e6ed;
        box-shadow: 0 2px 10px rgba(44, 62, 80, 0.08);
        background: white;
        border-left: 4px solid #2c3e50;
    }
    
    
    .maintenance-header {
        background: #2c3e50;
        color: white;
        border-radius: 8px 8px 0 0;
        padding: 16px 20px;
        border-bottom: 1px solid #34495e;
    }
    
    .maintenance-header h3 {
        margin: 0;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .maintenance-header i {
        margin-right: 10px;
        color: #3498db;
    }
    

    .maintenance-body {
        background-color: white;
        padding: 0;
    }
    
  
    .maintenance-full-width-table {
        width: 100%;
        min-width: 100%;
        table-layout: auto;
        background-color: white;
        border-collapse: collapse;
    }
    
    
    .maintenance-full-width-table thead {
        background: #2c3e50;
        color: white;
        border-bottom: 2px solid #3498db;
    }
    
    .maintenance-full-width-table th {
        padding: 16px 18px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        border: none;
        color: white;
        background: #2c3e50;
    }
    
    .maintenance-full-width-table th:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background: rgba(255, 255, 255, 0.2);
    }
    
    
    .maintenance-full-width-table tbody tr {
        border-bottom: 1px solid #f0f4f8;
        transition: all 0.2s ease;
    }
    
    .maintenance-full-width-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }
    
    .maintenance-full-width-table tbody tr:nth-child(odd) {
        background-color: white;
    }
    
    .maintenance-full-width-table tbody tr:hover {
        background-color: #edf2f7;
    }
    
    .maintenance-full-width-table td {
        padding: 14px 16px;
        color: #2c3e50;
        border: none;
        vertical-align: middle;
        font-size: 0.95rem;
    }
    
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
        min-width: 100px;
    }
    
    .status-planned {
        background-color: #3498db;
        color: white;
    }
    
    .status-in_progress {
        background-color: #f39c12;
        color: white;
    }
    
    .status-completed {
        background-color: #27ae60;
        color: white;
    }
    
    .status-cancelled {
        background-color: #e74c3c;
        color: white;
    }
    
    
    .maintenance-full-width-table .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
    }
    
    .maintenance-full-width-table .action-buttons .btn-small {
        width: 36px;
        height: 36px;
        padding: 0;
        font-size: 0.9rem;
        border-radius: 6px;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .maintenance-full-width-table .action-buttons .btn-small i {
        pointer-events: none;
    }

    /* Bouton Voir */
    .maintenance-full-width-table .action-buttons .btn-info {
        background-color: #e8f4fd;
        color: #3498db;
        border: 1px solid #b3d9ff;
    }
    .maintenance-full-width-table .action-buttons .btn-info:hover {
        background-color: #3498db;
        color: white;
    }

    /* Bouton Modifier */
    .maintenance-full-width-table .action-buttons .btn-warning {
        background-color: #fff4e6;
        color: #f39c12;
        border: 1px solid #ffd9b3;
    }
    .maintenance-full-width-table .action-buttons .btn-warning:hover {
        background-color: #f39c12;
        color: white;
    }

    /* Bouton Supprimer */
    .maintenance-full-width-table .action-buttons .btn-danger {
        background-color: #ffe6e6;
        color: #e74c3c;
        border: 1px solid #ffb3b3;
    }
    .maintenance-full-width-table .action-buttons .btn-danger:hover {
        background-color: #e74c3c;
        color: white;
    }
  
    .maintenance-full-width-container .table-container {
        width: 100%;
        overflow-x: auto;
        margin: 0;
        border-radius: 0 0 8px 8px;
        overflow: hidden;
    }
    
   
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #7f8c8d;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #bdc3c7;
        margin-bottom: 15px;
    }
    
    
    .main-content {
        padding: 20px;
        width: 100%;
        box-sizing: border-box;
        background-color: #f5f7fa;
    }
    
    .dashboard-header {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(44, 62, 80, 0.1);
        border-left: 4px solid #2c3e50;
    }
    
    .dashboard-header h1 {
        color: #2c3e50;
        margin-bottom: 5px;
        font-size: 1.8rem;
    }
    
    .dashboard-header .subtitle {
        color: #7f8c8d;
        margin-bottom: 0;
        font-size: 1rem;
    }
    
    
    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(41, 128, 185, 0.2);
    }
    
    /* Alertes */
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 20px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 20px;
    }
    
   
    @media (max-width: 768px) {
        .main-content {
            padding: 15px 10px;
        }
        
        .maintenance-full-width-table th,
        .maintenance-full-width-table td {
            padding: 12px 10px;
            font-size: 0.85em;
        }
        
        .maintenance-header {
            padding: 12px 15px;
        }
      
        .dashboard-header {
            padding: 15px;
        }
        
        .dashboard-header h1 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection
