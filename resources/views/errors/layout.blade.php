@extends('base.layout', [
    'layout' => 'frontend',
    'view' => 'frontend.error',
])

@section('layout-title')
    @yield('title')
@endsection

@section('layout-content')
    <div class="site-container">
        <div class="site-content">
            <main>
                @yield('content')
            </main>

            <div class="error-cta">
                <a href="/" class="btn btn-primary">
                    {{ __('errors/layout.go-back') }}
                </a>
            </div>
        </div>
    </div>
@endsection
