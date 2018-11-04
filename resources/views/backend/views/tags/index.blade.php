@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--views--tags--index',
])

@section('title', 'Tags')

@section('content')
    @include('backend.views.tags.index.modals.create-tag')
    @include('backend.views.tags.index.modals.edit-tag')
    @include('backend.views.tags.index.header')

    <div id="tags-loader" data-loader-type="tile">
        @include('backend.views.tags.index.filters')
        @include('backend.views.tags.index.table')
    </div>
@endsection
