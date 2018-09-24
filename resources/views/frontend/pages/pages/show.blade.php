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
    <main class="content">
        <header class="content-header">
            @can('edit', $page)
                <a href="{{ route('backend.pages.edit', $page) }}" class="btn btn-primary btn-icon-only">
                    <i class="fa fa-edit"></i>
                </a>
            @endcan

            <h1 class="content-title">
                {{ $pageVariant->title }}
            </h1>
        </header>

        <article class="page-content">
            {!! $renderedPageVariant->getContent() !!}
        </article>
    </main>

    <footer class="content-footer">
        @if ($page->attachments->isNotEmpty())
            <div class="page-attachments">
                <h5>Attachments</h5> {{-- @todo translation --}}
            </div>
        @endif

        {{-- @todo footer --}}
    </footer>
@endsection
