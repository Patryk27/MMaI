@php
    /**
     * @var string $pageClass
     */
@endphp

@extends('base.layout', [
    'layoutClass' => 'frontend frontend--layout',
    'pageClass' => $pageClass,
])

@section('layout-title')
    @yield('title')
@endsection

@section('layout-content')
    <div class="site-container">
        <div class="content">
            @yield('content')
        </div>

        @include('frontend.layout.navigation')
    </div>
@endsection
