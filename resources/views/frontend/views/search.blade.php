@php
    /**
     * @var string $query
     * @var \Illuminate\Support\Collection|\App\Pages\ValueObjects\RenderedPage[] $renderedPages
     */
@endphp

@extends('frontend.layout', [
    'view' => 'frontend.search',
])

@section('title', config('app.name'))

@section('content')
    @include('frontend.views.search.form')
    @include('frontend.views.search.results')
@endsection
