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
