@php
    /**
     * @var \App\Pages\Models\Page $page
     */

    $pagePresenter = $page->getPresenter();
@endphp

@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--pages--edit',
])

@section('title', 'Editing page')

@section('content')
    <div class="content-header">
        <h1 class="title">
            Editing {{ mb_strtolower($pagePresenter->getTranslatedType()) }} #{{ $page->id }}
        </h1>
    </div>

    <div id="page-form" data-method="put" data-url="{{ route('backend.pages.update', $page->id) }}">
        @include('backend.pages.pages.create-edit.form')
    </div>
@endsection
