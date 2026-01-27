@extends('layouts.app')

@section('content')

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
        <h1 style="margin: 0; color: #0a2a43;">Mes Réservations</h1>
        @if(!Auth::user()->isResponsable() && !Auth::user()->isAdmin())
            <a href="{{ route('reservations.create') }}" style="padding: 10px 20px; background-color: #3429d3; color: white; text-decoration: none; border-radius: 6px; font-weight: 600;">+ Nouvelle Réservation</a>
        @endif
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #10b981;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #fee2e2; color: #7f1d1d; padding: 12px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #ef4444;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Empty State or Table --}}
    @if($reservations->isEmpty())
        <div style="background-color: white; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <p style="color: #6b7280;">Aucune réservation trouvée.</p>
        </div>
    @else
        <div style="background-color: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #0a2a43; color: white;">
                        <th style="padding: 12px; text-align: left;">Utilisateur</th>
                        <th style="padding: 12px; text-align: left;">Ressource</th>
                        <th style="padding: 12px; text-align: left;">Catégorie</th>
                        <th style="padding: 12px; text-align: left;">Début</th>
                        <th style="padding: 12px; text-align: left;">Fin</th>
                        <th style="padding: 12px; text-align: left;">Statut</th>
                        <th style="padding: 12px; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px;">{{ $reservation->user->name }}</td>
                            <td style="padding: 12px;">{{ $reservation->resource->name }}</td>
                            <td style="padding: 12px;">{{ $reservation->resource->category->name }}</td>
                            <td style="padding: 12px; font-size: 14px;">{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</td>
                            <td style="padding: 12px; font-size: 14px;">{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}</td>
                            <td style="padding: 12px;">
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;
                                    @if($reservation->status == 'pending') background-color: #fef3c7; color: #92400e;
                                    @elseif($reservation->status == 'approved') background-color: #d1fae5; color: #065f46;
                                    @elseif($reservation->status == 'rejected') background-color: #fee2e2; color: #7f1d1d;
                                    @elseif($reservation->status == 'active') background-color: #dbeafe; color: #0c2340;
                                    @else background-color: #e5e7eb; color: #374151;
                                    @endif
                                ">
                                    @if($reservation->status == 'pending')
                                        En attente
                                    @elseif($reservation->status == 'approved')
                                        Approuvée
                                    @elseif($reservation->status == 'rejected')
                                        Rejetée
                                    @elseif($reservation->status == 'active')
                                        Active
                                    @else
                                        {{ ucfirst($reservation->status) }}
                                    @endif
                                </span>
                            </td>
                            <td style="padding: 12px; font-size: 14px;">
                                <a href="{{ route('reservations.show', $reservation->id) }}" style="display: inline-block; padding: 6px 12px; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 4px; margin-right: 4px; font-weight: 600;">Voir</a>
                                @if(Auth::user()->isAdmin())
                                    @if($reservation->status === 'pending')
                                        <form method="POST" action="{{ route('reservations.approve', $reservation->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="padding: 6px 12px; background-color: #10b981; color: white; border: none; border-radius: 4px; margin-right: 4px; font-weight: 600; cursor: pointer;">Approuver</button>
                                        </form>
                                    @endif
                                    <button type="button" style="padding: 6px 12px; background-color: #f59e0b; color: white; border: none; border-radius: 4px; font-weight: 600; cursor: pointer;" onclick="openRejectModal({{ $reservation->id }})">Rejeter</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Modal de rejet --}}
<div id="rejectModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
    <div style="background-color: white; margin: 10% auto; padding: 30px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); max-width: 500px; width: 90%;">
        <span style="color: #6b7280; float: right; font-size: 28px; font-weight: bold; cursor: pointer; transition: color 0.3s ease;" onclick="closeRejectModal()" onmouseover="this.style.color='#1f2937';" onmouseout="this.style.color='#6b7280';">&times;</span>
        <h2 style="color: #0a2a43; margin-top: 0;">Rejeter la Réservation</h2>
        <form id="rejectForm" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #0a2a43;">Raison du rejet:</label>
                <textarea name="rejection_reason" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px; min-height: 100px; font-family: inherit;" placeholder="Expliquez la raison du rejet..."></textarea>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <button type="submit" style="padding: 10px 20px; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Rejeter</button>
                <button type="button" style="padding: 10px 20px; background-color: #6b7280; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;" onclick="closeRejectModal()">Annuler</button>
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
