@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Liste des maintenances</h2>

    @if($maintenances->isEmpty())
        <p>Aucune maintenance.</p>
    @else
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Date</th>
            </tr>

            @foreach($maintenances as $maintenance)
                <tr>
                    <td>{{ $maintenance->id }}</td>
                    <td>{{ $maintenance->description ?? '---' }}</td>
                    <td>{{ $maintenance->created_at }}</td>
                </tr>
            @endforeach
        </table>
    @endif
</div>
@endsection
