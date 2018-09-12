@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--pages--index',
])

@section('title', 'Pages')

@section('content')
    @include('backend.pages.pages.index.header')

    <div id="pages-loader" data-loader-type="tile">
        @include('backend.pages.pages.index.filters')
        @include('backend.pages.pages.index.table')
    </div>
@endsection
