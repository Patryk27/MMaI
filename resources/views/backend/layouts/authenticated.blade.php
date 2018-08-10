@php
    /**
     * @var string $pageClass
     */
@endphp

@extends('base.layout', [
    'layoutClass' => 'backend backend--layouts--authenticated',
    'pageClass' => $pageClass,
])

@section('layout-title')
    {{ config('app.name') }} - @yield('title')
@endsection

@section('layout-content')
    <div class="site-container">
        @include('backend.layouts.authenticated.navigation')

        <div class="content-container">
            @include('base.components.layout.messages')

            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>
@endsection