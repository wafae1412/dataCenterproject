@extends('layouts.app')

@section('title', 'Notifications')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/notifications/index.css') }}">
@endpush

@section('content')
<div class="notifications-container">
    <div class="page-header">
        <h1><i class="fas fa-bell"></i> Vos Notifications</h1>
    </div>

    @if($notifications->isEmpty())
        <div class="empty-state">
            <i class="fas fa-bell-slash"></i>
            <p>Vous n'avez aucune notification pour le moment.</p>
        </div>
    @else
        <div class="notifications-list">
            @foreach($notifications as $notification)
                <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}">
                    <div class="notification-icon">
                        <i class="fas {{ $notification->is_read ? 'fa-envelope-open' : 'fa-envelope' }}"></i>
                    </div>
                    <div class="notification-content">
                        <p class="message">{{ $notification->message }}</p>
                        <span class="date"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    @if(!$notification->is_read)
                        <div class="notification-actions">
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-mark-read" title="Marquer comme lu">
                                    <i class="fas fa-check"></i> Lu
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection