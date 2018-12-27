@php
    /**
     * @var string $view
     */
@endphp

@extends('base.layout', [
    'layout' => 'backend.authenticated',
    'view' => $view,
])

@section('layout-title')
    {{ config('app.name') }} - @yield('title')
@endsection

@section('layout-content')
    <div class="site-container">
        @include('backend.layouts.authenticated.navigation')

        <div class="content">
            @include('base.components.layout.messages')
            @yield('content')
        </div>
    </div>
@endsection
