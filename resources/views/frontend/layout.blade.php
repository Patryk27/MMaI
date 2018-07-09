@php
    /**
     * @var string $pageClass
     */
@endphp

@extends('base.layout', [
    'layoutClass' => 'frontend frontend--layout',
    'pageClass' => $pageClass,
])

@section('layout-content')
    <div class="site-container">
        <div class="content-container">
            @yield('content')
        </div>

        @include('frontend.layout.navigation')
    </div>
@endsection