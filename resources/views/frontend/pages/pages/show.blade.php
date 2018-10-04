@php
    /**
     * @var \App\Pages\ValueObjects\RenderedPageVariant $renderedPageVariant
     */

    $page = $renderedPageVariant->getPage();
    $pageVariant = $renderedPageVariant->getPageVariant();
@endphp

@extends('frontend.layout', [
    'pageClass' => 'frontend--pages--pages--show',
])

@section('layout-meta')
    @if (strlen($pageVariant->lead) > 0)
        <meta name="description" content="{{ $pageVariant->lead }}">
    @endif
@endsection

@section('title', $pageVariant->title)

@section('content')
    @include('frontend.pages.pages.show.header')

    <main>
        @include('frontend.pages.pages.show.content')
    </main>

    @if ($page->attachments->isNotEmpty())
        @include('frontend.pages.pages.show.attachments')
    @endif
@endsection
