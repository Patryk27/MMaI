@php
    /**
     * @var \App\Pages\Models\Page $page
     */
@endphp

@if (!$page->isPublished())
    <div class="content-alerts">
        <div class="alert alert-warning">
            This page has not been published - only you (as an administrator) can view it.
        </div>
    </div>
@endif

<header class="content-header">
    @can('edit', $page)
        <a href="{{ route('backend.pages.edit', $page) }}" class="btn btn-primary btn-icon-only">
            <i class="fa fa-edit"></i>
        </a>
    @endcan

    <h1 class="page-title">
        {{ $page->title }}
    </h1>

    <div class="page-variants">
        {{-- @todo --}}
    </div>
</header>
