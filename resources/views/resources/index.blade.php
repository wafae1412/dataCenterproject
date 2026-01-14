@extends('layouts.app')

@section('title', 'Gestion des Ressources')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/resources/index.css') }}">
@endsection

@section('content')
<div class="resources-index">
    <div class="page-header">
        <h1>Ressources du DataCenter</h1>

        @include('partials.alerts')

        <a href="{{ route('resources.create') }}" class="btn btn-primary">
            Nouvelle Ressource
        </a>
    </div>

    @include('resources.partials.filters')
    @include('resources.partials.stats')

    @if($resources->isEmpty())
        @include('resources.partials.empty-state')
    @else
        @include('resources.partials.table')
    @endif
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/resources/index.js') }}"></script>
@endsection
