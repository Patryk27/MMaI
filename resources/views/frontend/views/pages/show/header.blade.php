@php
    /**
     * @var \App\Pages\Models\Page $page
     * @var \App\Pages\Models\PageVariant $pageVariant
     */
@endphp

@if (!$pageVariant->isPublished())
    <div class="content-alerts">
        <div class="alert alert-warning">
            This page has not been published - only you (as the administrator) can view it.
        </div>
    </div>
@endif

<header class="content-header">
    {{-- "Edit" button --}}
    @can('edit', $page)
        <a href="{{ route('backend.pages.edit', $page) }}" class="btn btn-primary btn-icon-only">
            <i class="fa fa-edit"></i>
        </a>
    @endcan

    {{-- Page's title --}}
    <h1 class="page-title">
        {{ $pageVariant->title }}
    </h1>

    {{-- Other variants of this page --}}
    <div class="page-variants">
        @foreach ($page->pageVariants->sortBy('language.native_name') as $pageVariant)
            @can('show', $pageVariant)
                <a class="page-variant" href="{{ $pageVariant->route->getEntireUrl() }}">
                    <span class="flag-icon flag-icon-{{ $pageVariant->language->iso_3166_code }}"></span>
                </a>
            @endcan
        @endforeach
    </div>
</header>
