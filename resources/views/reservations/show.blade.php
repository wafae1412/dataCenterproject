@extends('layouts.app')

@section('title', 'Détails Réservation')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
    <style>
        /* Conteneur pleine largeur */
        .reservation-full-width-container {
            width: 100%;
            max-width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .details-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            width: 100%;
            border: 1px solid #e2e8f0;
        }

        .details-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 8px 8px 0 0;
        }

        .details-body {
            padding: 20px;
        }

        /* Tableau d'informations pleine largeur */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table th {
            width: 250px; /* Largeur fixe pour les étiquettes */
            background-color: #f1f5f9;
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
            font-weight: 600;
        }

        .info-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
        }

        .status-banner {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
            font-size: 1.1rem;
        }
    </style>
@endpush

@section('content')
<div class="reservation-full-width-container">
    <div class="details-card">
        <div class="details-header">
            <h1><i class="fas fa-file-alt"></i> Détails de la Réservation #{{ $reservation->id }}</h1>
            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="details-body">
            <div class="status-banner status-{{ $reservation->status }}">
                Statut actuel : {{ ucfirst($reservation->status) }}
            </div>

            <table class="info-table">
                <tbody>
                    <tr>
                        <th>Demandeur</th>
                        <td>
                            <strong>{{ $reservation->user->name }}</strong>
                            <br>
                            <span class="text-muted">{{ $reservation->user->email }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Ressource</th>
                        <td>
                            <strong>{{ $reservation->resource->name }}</strong>
                            <span class="badge">{{ $reservation->resource->category->name ?? 'Non catégorisé' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Période</th>
                        <td>
                            Du <strong>{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y à H:i') }}</strong><br>
                            Au <strong>{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y à H:i') }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Quantité</th>
                        <td>{{ $reservation->quantity }}</td>
                    </tr>
                    <tr>
                        <th>Justification</th>
                        <td>{{ $reservation->justification }}</td>
                    </tr>
                    @if($reservation->rejection_reason)
                    <tr>
                        <th>Motif du rejet</th>
                        <td style="color: var(--danger);">{{ $reservation->rejection_reason }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            @if(auth()->user()->isAdmin() || auth()->user()->isResponsable())
                @if($reservation->status === 'pending')
                    <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                        <form action="{{ route('reservations.approve', $reservation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Approuver
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" onclick="document.getElementById('rejectModal').style.display='block'">
                            <i class="fas fa-times"></i> Rejeter
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Modal Rejet -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('rejectModal').style.display='none'">&times;</span>
        <h2>Rejeter la demande</h2>
        <form action="{{ route('reservations.reject', $reservation->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Motif du rejet</label>
                <textarea name="rejection_reason" class="form-control" required rows="3"></textarea>
            </div>
            <div class="form-actions" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('rejectModal').style.display='none'">Annuler</button>
                <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fermer le modal si on clique en dehors
    window.onclick = function(event) {
        var modal = document.getElementById('rejectModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
@endsection