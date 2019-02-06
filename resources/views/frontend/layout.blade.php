@php
    /**
     * @var string $view
     */
@endphp

@extends('base.layout', [
    'layout' => 'frontend',
    'view' => $view,
])

@section('layout-title')
    @yield('title')
@endsection

@section('layout-content')
    <div class="site-container">
        @include('frontend.components.layout.navigation')

        <div class="site-content">
            @yield('content')
        </div>
    </div>
@endsection
