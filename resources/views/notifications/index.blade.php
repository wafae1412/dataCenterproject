@extends('layouts.app')

@section('content')

<h2>My Notifications</h2>

@foreach($notifications as $notification)
    <div>
        {{ $notification->message }}

        @if(!$notification->is_read)
            <form method="POST" action="/notifications/{{ $notification->id }}/read">
                @csrf
                <button>Mark as read</button>
            </form>
        @endif
    </div>
@endforeach

@endsection
