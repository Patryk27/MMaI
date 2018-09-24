@php
    /**
     * @var \App\Pages\Models\Page $page
     * @var \App\Pages\Models\PageVariant $pageVariant
     */
@endphp

<header class="content-header">
    @can('edit', $page)
        <a href="{{ route('backend.pages.edit', $page) }}" class="btn btn-primary btn-icon-only">
            <i class="fa fa-edit"></i>
        </a>
    @endcan

    <h1 class="page-title">
        {{ $pageVariant->title }}
    </h1>
</header>
