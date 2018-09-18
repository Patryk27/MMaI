@extends('base.layout', [
    'layoutClass' => 'frontend frontend--layout',
    'pageClass' => 'frontend--pages--error',
])

@section('layout-title')
    @yield('title')
@endsection

@section('layout-content')
    <div class="site-container">
        <div class="content-container">
            <main class="content">
                @yield('content')

                <div class="error-cta">
                    <a href="/" class="btn btn-primary">
                        {{-- @todo translation --}}
                        Go back to the home page
                    </a>
                </div>
            </main>
        </div>
    </div>
@endsection
