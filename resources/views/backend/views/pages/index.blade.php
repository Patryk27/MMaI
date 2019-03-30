@extends('backend.layouts.authenticated', [
    'view' => 'backend.pages.index',
])

@section('title', 'Pages')

@section('content')
    <div class="content-header">
        <h1 class="title">
            Pages
        </h1>

        <div class="toolbar">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                    Create new
                </button>

                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('backend.pages.create-page') }}">
                        Page
                    </a>

                    <a class="dropdown-item" href="{{ route('backend.pages.create-post') }}">
                        Post
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="pages-loader" data-loader-type="tile">
        <div id="pages-grid" class="grid"></div>
    </div>
@endsection
