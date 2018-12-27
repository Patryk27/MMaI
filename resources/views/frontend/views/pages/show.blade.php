@php
    /**
     * @var \App\Pages\ValueObjects\RenderedPage $renderedPage
     */

    $page = $renderedPage->getPage();
@endphp

@extends('frontend.layout', [
    'view' => 'frontend.pages.show',
])

@section('layout-meta')
    @if (strlen($page->lead) > 0) {{-- @todo $page->hasLead() --}}
        <meta name="description" content="{{ $page->lead }}">
    @endif
@endsection

@section('title', $page->title)

@section('content')
    @include('frontend.views.pages.show.header')

    <main>
        @include('frontend.views.pages.show.content')
    </main>

    @if ($page->attachments->isNotEmpty())
        @include('frontend.views.pages.show.attachments')
    @endif
@endsection
