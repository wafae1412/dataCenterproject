@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Mes Réservations</h1>
        @if(!Auth::user()->isResponsable() && !Auth::user()->isAdmin())
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvelle Réservation
            </a>
        @endif
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if($reservations->isEmpty())
        <div class="empty-state">
            <i class="fas fa-calendar-times" style="font-size:3rem; margin-bottom:1rem; opacity:0.5;"></i>
            <h3>Aucune réservation</h3>
            <p>Vous n'avez aucune réservation en cours.</p>
        </div>
    @else
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Ressource</th>
                        <th>Quantité</th>
                        <th>Période</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $reservation->user->name }}</div>
                            </td>
                            <td>
                                <div>{{ $reservation->resource->name }}</div>
                                <small style="color: var(--text-light);">{{ $reservation->resource->category->name }}</small>
                            </td>
                            <td>{{ $reservation->quantity }}</td>
                            <td>
                                <div style="font-size: 0.85rem;">
                                    <div><i class="fas fa-arrow-right" style="color: var(--success); font-size: 0.7rem;"></i> {{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</div>
                                    <div><i class="fas fa-arrow-left" style="color: var(--danger); font-size: 0.7rem;"></i> {{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $reservation->status }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-small btn-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if(Auth::user()->isAdmin() || Auth::user()->isResponsable())
                                        @if($reservation->status === 'pending')
                                            <form method="POST" action="{{ route('reservations.approve', $reservation->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-small btn-success" title="Approuver">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-small btn-warning" onclick="openRejectModal({{ $reservation->id }})" title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Modal Rejet (kept functionally identical, styled minimally) --}}
<div id="rejectModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; width:100%; max-width:500px; padding:2rem; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.2); margin: 2rem auto; position: relative; top: 20%;">
        <div style="display:flex; justify-content:space-between; margin-bottom:1.5rem; align-items: center;">
            <h2 style="margin:0; font-size: 1.5rem; color: var(--primary);">Rejeter la Réservation</h2>
            <span onclick="closeRejectModal()" style="cursor:pointer; font-size:1.5rem; color: var(--text-light);">&times;</span>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:600; color: var(--text);">Raison du rejet:</label>
                <textarea name="rejection_reason" required placeholder="Expliquez la raison du rejet..." style="width:100%; min-height:100px; padding:0.75rem; border:1px solid #e2e8f0; border-radius:8px; font-family: inherit;"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" class="btn btn-small btn-info" style="background-color:#cbd5e1; color: #334155;" onclick="closeRejectModal()">Annuler</button>
                <button type="submit" class="btn btn-small btn-danger">Confirmer Rejet</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush