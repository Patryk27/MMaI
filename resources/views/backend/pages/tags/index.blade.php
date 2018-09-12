@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--tags--index',
])

@section('title', 'Tags')

@section('content')
    @include('backend.pages.tags.index.modals.create-tag')
    @include('backend.pages.tags.index.modals.edit-tag')

    @include('backend.pages.tags.index.header')

    <div id="tags-loader" data-loader-type="tile">
        @include('backend.pages.tags.index.filters')
        @include('backend.pages.tags.index.table')
    </div>
@endsection
