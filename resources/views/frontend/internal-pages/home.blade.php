@php
    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Pages\ValueObjects\RenderedPage[] $posts
     */
@endphp

@extends('frontend.layout', [
    'pageClass' => 'frontend--pages--pages--show',
])

@section('content')
    @include('frontend.components.posts.list', [
        'posts' => $posts,
    ])

    {{ $posts->links() }}
@endsection