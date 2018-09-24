@php
    /**
     * @var string $pageClass
     */
@endphp

@extends('base.layout', [
    'layoutClass' => 'backend backend--layouts--guest',
    'pageClass' => $pageClass,
])

@section('layout-title')
    {{ config('app.name') }} - @yield('title')
@endsection

@section('layout-content')
    <div class="site-container">
        <h1>
            {{ config('app.name') }}
        </h1>

        @include('base.components.layout.messages')

        <div class="content">
            @yield('content')
        </div>
    </div>
@endsection
