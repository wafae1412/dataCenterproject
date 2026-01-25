@extends('layouts.app')

@section('content')
<h2>Mes Notifications</h2>

@if($notifications->isEmpty())
    <p>Aucune notification pour le moment.</p>
@else
    @foreach($notifications as $notification)
        <div>
            {{ $notification->message }}

            @if(!$notification->is_read)
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                    @csrf
                    <button>Marquer comme lue</button>
                </form>
            @endif
        </div>
    @endforeach
@endif
@endsection
