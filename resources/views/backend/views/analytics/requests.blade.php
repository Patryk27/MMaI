@extends('backend.layouts.authenticated', [
    'view' => 'backend.analytics.requests',
])

@section('title', 'Latest requests')

@section('content')
    @include('backend.views.analytics.requests.header')

    <div id="requests-loader" data-loader-type="tile">
        @include('backend.views.analytics.requests.filters')
        @include('backend.views.analytics.requests.table')
    </div>
@endsection
