@extends('layouts.app')

@section('content')

<div style="max-width: 900px; margin: 0 auto; padding: 20px;">
    {{-- Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e5e7eb;">
        <h1 style="margin: 0; color: #0a2a43;">Détails de la Réservation</h1>
        <a href="{{ route('reservations.index') }}" style="color: #3429d3; text-decoration: none;">← Retour</a>
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

    <div style="background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden;">
        <div style="background: linear-gradient(135deg, #3429d3 0%, #2318c0 100%); color: white; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="margin: 0; color: white;">Réservation #{{ $reservation->id }}</h2>
            <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;
                @if($reservation->status == 'pending') background-color: #fef3c7; color: #92400e;
                @elseif($reservation->status == 'approved') background-color: #d1fae5; color: #065f46;
                @elseif($reservation->status == 'rejected') background-color: #fee2e2; color: #7f1d1d;
                @else background-color: #dbeafe; color: #0c2340;
                @endif
            ">
                {{ ucfirst($reservation->status) }}
            </span>
        </div>

        <div style="padding: 2rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <div style="border-left: 4px solid #3429d3; padding-left: 1rem;">
                    <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase;">Utilisateur</label>
                    <p style="color: #1f2937; font-size: 1.1rem;">{{ $reservation->user->name }} ({{ $reservation->user->email }})</p>
                </div>

                <div style="border-left: 4px solid #3429d3; padding-left: 1rem;">
                    <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase;">Ressource</label>
                    <p style="color: #1f2937; font-size: 1.1rem;">{{ $reservation->resource->name }}</p>
                </div>

                <div style="border-left: 4px solid #3429d3; padding-left: 1rem;">
                    <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase;">Catégorie</label>
                    <p style="color: #1f2937; font-size: 1.1rem;">{{ $reservation->resource->category->name }}</p>
                </div>

                <div style="border-left: 4px solid #3429d3; padding-left: 1rem;">
                    <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase;">Date de Début</label>
                    <p style="color: #1f2937; font-size: 1.1rem;">{{ \Carbon\Carbon::parse($reservation->date_start)->format('d/m/Y H:i') }}</p>
                </div>

                <div style="border-left: 4px solid #3429d3; padding-left: 1rem;">
                    <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase;">Date de Fin</label>
                    <p style="color: #1f2937; font-size: 1.1rem;">{{ \Carbon\Carbon::parse($reservation->date_end)->format('d/m/Y H:i') }}</p>
                </div>

                <div style="border-left: 4px solid #3429d3; padding-left: 1rem;">
                    <label style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase;">Durée</label>
                    <p style="color: #1f2937; font-size: 1.1rem;">
                        @php
                            $duration = \Carbon\Carbon::parse($reservation->date_start)->diffInHours($reservation->date_end);
                            echo $duration . ' heure(s)';
                        @endphp
                    </p>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Justification</label>
                <div style="background-color: #f9fafb; padding: 1rem; border-radius: 6px; border-left: 4px solid #3429d3; line-height: 1.8;">
                    {{ $reservation->justification }}
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Spécifications de la Ressource</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                    <div style="background-color: #f9fafb; padding: 1rem; border-radius: 6px; border: 1px solid #e5e7eb;">
                        <span style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem;">CPU:</span>
                        <span style="display: block; font-size: 1.1rem; color: #1f2937; font-weight: 600;">{{ $reservation->resource->cpu }} core(s)</span>
                    </div>
                    <div style="background-color: #f9fafb; padding: 1rem; border-radius: 6px; border: 1px solid #e5e7eb;">
                        <span style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem;">RAM:</span>
                        <span style="display: block; font-size: 1.1rem; color: #1f2937; font-weight: 600;">{{ $reservation->resource->ram }}GB</span>
                    </div>
                    <div style="background-color: #f9fafb; padding: 1rem; border-radius: 6px; border: 1px solid #e5e7eb;">
                        <span style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem;">Stockage:</span>
                        <span style="display: block; font-size: 1.1rem; color: #1f2937; font-weight: 600;">{{ $reservation->resource->storage }}GB</span>
                    </div>
                    @if($reservation->resource->location)
                        <div style="background-color: #f9fafb; padding: 1rem; border-radius: 6px; border: 1px solid #e5e7eb;">
                            <span style="display: block; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; font-size: 0.85rem;">Localisation:</span>
                            <span style="display: block; font-size: 1.1rem; color: #1f2937; font-weight: 600;">{{ $reservation->resource->location }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 1rem; color: #1f2937;">Dates de Création/Modification</label>
                <p style="color: #6b7280; margin: 0;">Créée le: {{ $reservation->created_at->format('d/m/Y H:i') }}</p>
                <p style="color: #6b7280;">Modifiée le: {{ $reservation->updated_at->format('d/m/Y H:i') }}</p>
            </div>

            @if(Auth::user()->isAdmin() && $reservation->status === 'pending')
            <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <form method="POST" action="{{ route('reservations.approve', $reservation->id) }}" style="flex: 1;">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 0.75rem 1.5rem; background-color: #10b981; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Approuver</button>
                </form>

                <button type="button" style="flex: 1; padding: 0.75rem 1.5rem; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;" onclick="openRejectModal()">Rejeter</button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div id="rejectModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);">
    <div style="background-color: white; margin: 5% auto; padding: 2rem; border-radius: 10px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3); max-width: 500px; width: 90%;">
        <span style="color: #6b7280; float: right; font-size: 1.5rem; font-weight: bold; cursor: pointer; transition: color 0.3s ease;" onclick="closeRejectModal()" onmouseover="this.style.color='#1f2937'" onmouseout="this.style.color='#6b7280'">&times;</span>
        <h2 style="color: #0a2a43; margin-top: 0;">Rejeter la Réservation</h2>
        <form method="POST" action="{{ route('reservations.reject', $reservation->id) }}">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #1f2937;">Raison du rejet:</label>
                <textarea name="rejection_reason" required placeholder="Expliquez la raison du rejet..." rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 0.95rem;"></textarea>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <button type="submit" style="padding: 0.75rem 1.5rem; background-color: #ef4444; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Rejeter</button>
                <button type="button" style="padding: 0.75rem 1.5rem; background-color: #6b7280; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;" onclick="closeRejectModal()">Annuler</button>
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
