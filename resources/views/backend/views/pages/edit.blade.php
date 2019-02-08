@php
    /**
     * @var \App\Pages\Models\Page $page
     */

    $pagePresenter = $page->getPresenter();
@endphp

@extends('backend.layouts.authenticated', [
    'view' => 'backend.pages.edit',
])

@section('title', 'Editing page')

@section('content')
    <div class="content-header">
        <h1 class="title">
            Editing {{ mb_strtolower($pagePresenter->getTranslatedType()) }} #{{ $page->id }}
        </h1>
    </div>

    <div id="form">
        @include('backend.views.pages.create-edit.form')
    </div>
@endsection
