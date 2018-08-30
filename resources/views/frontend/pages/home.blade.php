@php
    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Pages\ValueObjects\RenderedPageVariant[] $posts
     */
@endphp

@extends('frontend.layout', [
    'pageClass' => 'frontend--pages--pages--show',
])

@section('title', config('app.name'))

@section('content')
    @include('frontend.components.posts.list', [
        'posts' => $posts,
    ])

    {{ $posts->links() }}
@endsection