@extends('layouts.app')

@section('content')
<script>
    // Redirect users to their dashboard
    window.location.href = '{{ route("dashboard") }}';
</script>
@endsection
