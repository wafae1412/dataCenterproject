@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1>Mes Réservations</h1>
            <p>Historique de toutes vos réservations</p>
        </div>
        <a href="{{ route('reservations.create') }}" class="btn btn-primary">
            + Nouvelle réservation
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($reservations->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">📋</div>
            <h3>Aucune réservation</h3>
            <p>Vous n'avez pas encore effectué de réservation</p>
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                Créer ma première réservation
            </a>
        </div>
    @else
        <div class="reservations-grid">
            @foreach($reservations as $reservation)
                <div class="reservation-card">
                    <div class="card-header">
                        <h3>{{ $reservation->resource->name }}</h3>
                        <span class="status-badge status-{{ $reservation->status }}">
                            @if($reservation->status === 'pending')
                                ⏳ En attente
                            @elseif($reservation->status === 'approved')
                                ✓ Validée
                            @elseif($reservation->status === 'rejected')
                                ✗ Refusée
                            @else
                                {{ $reservation->status }}
                            @endif
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="info-row">
                            <span class="label">Type :</span>
                            <span class="value">{{ $reservation->resource->type }}</span>
                        </div>

                        <div class="info-row">
                            <span class="label">Période :</span>
                            <span class="value">
                                Du {{ $reservation->date_start->format('d/m/Y à H:i') }}<br>
                                Au {{ $reservation->date_end->format('d/m/Y à H:i') }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="label">Durée :</span>
                            <span class="value">
                                {{ $reservation->date_start->diffInDays($reservation->date_end) }} jours
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="label">Demandé le :</span>
                            <span class="value">{{ $reservation->created_at->format('d/m/Y à H:i') }}</span>
                        </div>

                        @if($reservation->status === 'rejected' && $reservation->rejection_reason)
                            <div class="rejection-reason">
                                <strong>Motif du refus :</strong>
                                <p>{{ $reservation->rejection_reason }}</p>
                            </div>
                        @endif

                        @if($reservation->status === 'approved' && $reservation->validated_at)
                            <div class="validation-info">
                                <small>Validée le {{ $reservation->validated_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-secondary btn-sm">
                            Voir détails
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-wrapper">
            {{ $reservations->links() }}
        </div>
    @endif
</div>

<style>
.container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-header h1 {
    font-size: 28px;
    color: #1a1a1a;
    margin-bottom: 5px;
}

.page-header p {
    color: #666;
    font-size: 14px;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
}

.empty-state p {
    color: #666;
    margin-bottom: 30px;
}

.reservations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.reservation-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}

.reservation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.card-header {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    font-size: 18px;
    margin: 0;
    font-weight: 600;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-approved {
    background-color: #d4edda;
    color: #155724;
}

.status-rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.card-body {
    padding: 20px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row .label {
    color: #666;
    font-size: 13px;
    font-weight: 600;
}

.info-row .value {
    color: #333;
    font-size: 13px;
    text-align: right;
}

.rejection-reason {
    margin-top: 15px;
    padding: 15px;
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
    border-radius: 4px;
}

.rejection-reason strong {
    display: block;
    margin-bottom: 8px;
    color: #856404;
}

.rejection-reason p {
    margin: 0;
    color: #856404;
    font-size: 13px;
}

.validation-info {
    margin-top: 15px;
    padding: 10px;
    background-color: #d4edda;
    border-radius: 4px;
    text-align: center;
}

.validation-info small {
    color: #155724;
    font-size: 12px;
}

.card-footer {
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-top: 1px solid #e0e0e0;
    text-align: right;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

.btn-secondary {
    background-color: #ecf0f1;
    color: #333;
}

.btn-secondary:hover {
    background-color: #bdc3c7;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 13px;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .reservations-grid {
        grid-template-columns: 1fr;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>
@endsection