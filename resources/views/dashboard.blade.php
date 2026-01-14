<x-app-layout>
    <x-slot name="header">
        <h2>Dashboard</h2>
    </x-slot>

    <div class="p-6">
        <p>Bienvenue {{ auth()->user()->name }}</p>
        <p>Role: {{ auth()->user()->role->name }}</p>

        @if(auth()->user()->role->name === 'Admin')
            <a href="/admin">Admin Dashboard</a>
        @endif

        @if(auth()->user()->role->name === 'Responsable')
            <a href="/responsable">Responsable Dashboard</a>
        @endif
    </div>
</x-app-layout>
