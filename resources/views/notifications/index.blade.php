@extends('layouts.app')

@section('content')

<div style="max-width: 1000px; margin: 2rem auto; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e5e7eb;">
        <h1 style="margin: 0; color: #0a2a43;">My Notifications</h1>
    </div>

    @if($notifications->isEmpty())
        <div style="background: white; border-radius: 10px; padding: 3rem 2rem; text-align: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <h3 style="color: #0a2a43;">Aucune notification</h3>
            <p style="color: #6b7280;">Vous êtes à jour avec toutes les notifications</p>
        </div>
    @else
        <div style="display: grid; gap: 1.5rem;">
            @foreach($notifications as $notification)
                <div style="background: white; border-left: 4px solid #3429d3; padding: 1.5rem; border-radius: 6px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex: 1;">
                        <p style="color: #1f2937; margin: 0 0 0.5rem 0;">{{ $notification->message }}</p>
                        <p style="color: #6b7280; font-size: 0.85rem; margin: 0;">{{ $notification->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    @if(!$notification->is_read)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" style="margin-left: 1rem;">
                            @csrf
                            <button style="padding: 0.5rem 1rem; background-color: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Mark as read</button>
                        </form>
                    @else
                        <span style="display: inline-block; padding: 0.5rem 1rem; background-color: #d1fae5; color: #065f46; border-radius: 6px; font-size: 0.85rem; font-weight: 600; margin-left: 1rem;">✓ Read</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
