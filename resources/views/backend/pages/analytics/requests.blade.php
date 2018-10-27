@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--analytics--requests',
])

@section('title', 'Latest requests')

@section('content')
    @include('backend.pages.analytics.requests.header')

    <div id="requests-loader" data-loader-type="tile">
        @include('backend.pages.analytics.requests.filters')
        @include('backend.pages.analytics.requests.table')
    </div>
@endsection
