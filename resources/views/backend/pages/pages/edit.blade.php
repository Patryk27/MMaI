@php
    /**
     * @var \App\Models\Page $page
     */
@endphp

@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--pages--edit',
])

@section('content')
    <div class="title-wrapper">
        <h1 class="title">
            Editing page #{{ $page->id }}
        </h1>
    </div>

    <div id="form"
         data-method="put"
         data-url="{{ route('backend.pages.update', $page->id) }}">
        @include('backend.pages.pages.create-edit.form', [
            'page' => $page,
        ])
    </div>
@endsection