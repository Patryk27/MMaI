@php
    /**
     * @var string $view
     */
@endphp

@extends('base.layout', [
    'layout' => 'backend.authenticated',
    'view' => $view,
])

@section('layout-meta')
    <script>
        window.config = {
            apiUrl: '//api.{{ env('APP_DOMAIN') }}',
        };
    </script>
@endsection

@section('layout-title')
    @yield('title') - {{ config('app.name') }}
@endsection

@section('layout-content')
    <div class="site-container">
        @include('backend.layouts.authenticated.navigation')

        <div class="site-content">
            @include('base.components.layout.messages')
            @yield('content')
        </div>
    </div>
@endsection
