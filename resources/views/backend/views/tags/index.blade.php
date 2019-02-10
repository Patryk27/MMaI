@extends('backend.layouts.authenticated', [
    'view' => 'backend.tags.index',
])

@section('title', 'Tags')

@section('content')
    <div class="content-header">
        <h1 class="title">
            Tags
        </h1>

        <div class="toolbar">
            <a id="create-tag-button" class="btn btn-primary">
                Create new tag
            </a>
        </div>
    </div>

    <div id="tags-loader" data-loader-type="tile">
        @include('backend.components.tags.table.filters')
        @include('backend.components.tags.table')
    </div>

    @include('backend.components.tags.form.create-modal')
    @include('backend.components.tags.form.edit-modal')
@endsection
