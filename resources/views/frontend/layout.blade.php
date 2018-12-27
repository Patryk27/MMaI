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
        <div class="content">
            @yield('content')
        </div>

        @include('frontend.components.layout.navigation')
    </div>
@endsection
