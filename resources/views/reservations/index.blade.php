@extends('layouts.app')

@section('content')
<div class="reservations-container">
    <div class="page-header">
        <h1>Mes Réservations</h1>
        @if(!Auth::user()->isResponsable() && !Auth::user()->isAdmin())
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">+ Nouvelle Réservation</a>
        @endif
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

    @if($reservations->isEmpty())
        <div class="empty-state">
            <p>Aucune réservation trouvée.</p>
        </div>
    @else
        <div class="reservations-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Ressource</th>
                        <th>Catégorie</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->resource->name }}</td>
                            <td>{{ $reservation->resource->category->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="status-badge status-{{ $reservation->status }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-small btn-info">Voir</a>
                                @if(Auth::user()->isAdmin())
                                    @if($reservation->status === 'pending')
                                        <form method="POST" action="{{ route('reservations.approve', $reservation->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-small btn-success">Approuver</button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-small btn-warning" onclick="openRejectModal({{ $reservation->id }})">Rejeter</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Modal de rejet -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRejectModal()">&times;</span>
        <h2>Rejeter la Réservation</h2>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-group">
                <label>Raison du rejet:</label>
                <textarea name="rejection_reason" required placeholder="Expliquez la raison du rejet..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Rejeter</button>
                <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id) {
        document.getElementById('rejectForm').action = '/reservations/' + id + '/reject';
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
