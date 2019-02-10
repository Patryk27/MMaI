@php
    /**
     * @var string $type
     */

    $page = new \App\Pages\Models\Page([
        'type' => $type,
    ]);

    $pagePresenter = $page->getPresenter();
    $translatedType = mb_strtolower($pagePresenter->getTranslatedType());
@endphp

@extends('backend.layouts.authenticated', [
    'view' => 'backend.pages.create',
])

@section('title', 'Creating new ' . $translatedType)

@section('content')
    <div class="content-header">
        <h1 class="title">
            Creating new {{ $translatedType }}
        </h1>
    </div>

    <div id="form">
        @include('backend.components.pages.form')
    </div>
@endsection
