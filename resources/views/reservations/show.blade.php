@extends('layouts.app')

@section('content')
<div class="reservation-detail-container">
    <div class="page-header">
        <h1>Détails de la Réservation</h1>
        <a href="{{ route('reservations.index') }}" class="btn btn-secondary">← Retour</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2>Réservation #{{ $reservation->id }}</h2>
            <span class="status-badge status-{{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
        </div>

        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Utilisateur</label>
                    <p>{{ $reservation->user->name }} ({{ $reservation->user->email }})</p>
                </div>

                <div class="detail-item">
                    <label>Ressource</label>
                    <p>{{ $reservation->resource->name }}</p>
                </div>

                <div class="detail-item">
                    <label>Catégorie</label>
                    <p>{{ $reservation->resource->category->name }}</p>
                </div>

                <div class="detail-item">
                    <label>Date de Début</label>
                    <p>{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</p>
                </div>

                <div class="detail-item">
                    <label>Date de Fin</label>
                    <p>{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}</p>
                </div>

                <div class="detail-item">
                    <label>Durée</label>
                    <p>
                        @php
                            $duration = \Carbon\Carbon::parse($reservation->date_start)->diffInHours($reservation->date_end);
                            echo $duration . ' heure(s)';
                        @endphp
                    </p>
                </div>
            </div>

            <div class="detail-section">
                <label>Justification</label>
                <div class="justification-box">
                    {{ $reservation->justification }}
                </div>
            </div>

            <div class="detail-section">
                <label>Spécifications de la Ressource</label>
                <div class="spec-grid">
                    <div class="spec-item">
                        <span class="spec-label">CPU:</span>
                        <span class="spec-value">{{ $reservation->resource->cpu }} core(s)</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">RAM:</span>
                        <span class="spec-value">{{ $reservation->resource->ram }}GB</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Stockage:</span>
                        <span class="spec-value">{{ $reservation->resource->storage }}GB</span>
                    </div>
                    @if($reservation->resource->location)
                        <div class="spec-item">
                            <span class="spec-label">Localisation:</span>
                            <span class="spec-value">{{ $reservation->resource->location }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="detail-section">
                <label>Dates de Création/Modification</label>
                <p>Créée le: {{ $reservation->created_at->format('d/m/Y H:i') }}</p>
                <p>Modifiée le: {{ $reservation->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        @if(Auth::user()->isAdmin() && $reservation->status === 'pending')
        <div class="card-footer">
            <form method="POST" action="{{ route('reservations.approve', $reservation->id) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success">Approuver</button>
            </form>

            <button type="button" class="btn btn-danger" onclick="openRejectModal()">Rejeter</button>
        </div>
        @endif
    </div>
</div>

<!-- Modal de rejet -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRejectModal()">&times;</span>
        <h2>Rejeter la Réservation</h2>
        <form method="POST" action="{{ route('reservations.reject', $reservation->id) }}">
            @csrf
            <div class="form-group">
                <label>Raison du rejet:</label>
                <textarea name="rejection_reason" required placeholder="Expliquez la raison du rejet..." rows="4"></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Rejeter</button>
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal() {
        document.getElementById('rejectModal').style.display = 'block';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('rejectModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection
