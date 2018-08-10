@php
    /**
     * @var \App\Pages\ValueObjects\RenderedPage $renderedPage
     */

    $page = $renderedPage->getPage();
    $pageVariant = $renderedPage->getPageVariant();
@endphp

@extends('frontend.layout', [
    'pageClass' => 'frontend--pages--pages--show',
])

@section('title', $pageVariant->title)

@section('content')
    <main class="content">
        <header>
            <h1>
                {{ $pageVariant->title }}
            </h1>
        </header>

        <article class="page-content">
            {!! $renderedPage->getContent() !!}
        </article>
    </main>

    @if ($page->isBlogPost())
        <footer class="content-footer">
            @include('frontend.components.post.footer', [
                'pageVariant' => $pageVariant,
            ])
        </footer>
    @endif
@endsection