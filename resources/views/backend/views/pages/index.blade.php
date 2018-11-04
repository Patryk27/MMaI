@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--views--pages--index',
])

@section('title', 'Pages')

@section('content')
    @include('backend.views.pages.index.header')

    <div id="pages-loader" data-loader-type="tile">
        @include('backend.views.pages.index.filters')
        @include('backend.views.pages.index.table')
    </div>
@endsection
