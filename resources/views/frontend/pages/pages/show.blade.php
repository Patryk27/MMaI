@php
    /**
     * @var \App\ValueObjects\RenderedPageVariant $renderedPageVariant
     */

    $page = $renderedPageVariant->getPage();
    $pageVariant = $renderedPageVariant->getPageVariant();
@endphp

@extends('frontend.layout', [
    'pageClass' => 'frontend--pages--pages--show',
])

@section('content')
    <main class="content">
        <header>
            <h1>
                {{ $pageVariant->title }}
            </h1>
        </header>

        <article class="page-content">
            {!! $renderedPageVariant->getContent() !!}
        </article>
    </main>

    @if ($page->isBlogPage())
        <footer class="content-footer">
            @include('frontend.components.post.footer', [
                'pageVariant' => $pageVariant,
            ])
        </footer>
    @endif
@endsection