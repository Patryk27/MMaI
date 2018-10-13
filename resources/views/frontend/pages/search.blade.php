@php
    /**
     * @var string $query
     * @var \Illuminate\Support\Collection|\App\Pages\ValueObjects\RenderedPageVariant[] $posts
     */
@endphp

@extends('frontend.layout', [
    'pageClass' => 'frontend--pages--search--index',
])

@section('title', config('app.name'))

@section('content')
    {{-- Search form --}}
    <form id="search-form" method="get" action="#">
        <div class="input-group">
            {{ Form::search('query', $query, [
                'class' => 'form-control input-light',
                'placeholder' => 'Search...',
                'aria-label' => 'Search',
                'autofocus' => true,
            ]) }}

            <div class="input-group-append">
                <button type="submit" class="btn btn-light btn-icon-only">
                    <i class="fa fa-search"></i>
                    <span class="sr-only">Search</span>
                </button>
            </div>
        </div>
    </form>

    {{-- Posts' counter --}}
    @if($posts->isEmpty())
        <div class="alert alert-warning">
            Unfortunately, no posts match given criteria. {{-- @todo translation --}}
        </div>
    @else
        <div class="alert alert-info">
            Found {{ $posts->count() }} post(s). {{-- @todo translation --}}
        </div>

        {{-- Posts --}}
        @include('frontend.components.posts.list', [
            'posts' => $posts,
        ])
    @endif
@endsection
