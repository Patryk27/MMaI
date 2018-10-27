@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--analytics--index',
])

@section('title', 'Analytics')

@section('content')
    <div class="content-header">
        <h1 class="title">
            Analytics
        </h1>
    </div>

    <div>
        <a href="{{ route('backend.analytics.requests') }}" class="btn btn-primary">
            Explore requests
        </a>
    </div>
@endsection
