@extends('base.layout', [
    'layoutClass' => 'frontend frontend--layout',
    'pageClass' => 'frontend--views--error',
])

@section('layout-title')
    @yield('title')
@endsection

@section('layout-content')
    <div class="site-container">
        <div class="content">
            <main>
                @yield('content')
            </main>

            <div class="error-cta">
                <a href="/" class="btn btn-primary">
                    {{-- @todo translation --}}
                    Go back to the home page
                </a>
            </div>
        </div>
    </div>
@endsection
