@php
    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Pages\ValueObjects\RenderedPage[] $renderedPages
     */
@endphp

@extends('frontend.layout', [
    'view' => 'frontend.home',
])

@section('title', config('app.name'))

@section('content')
    @include('frontend.components.pages.list', [
        'renderedPages' => $renderedPages,
    ])

    {{ $renderedPages->links() }}
@endsection
