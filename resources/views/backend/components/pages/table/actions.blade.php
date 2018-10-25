@php
    /**
     * @var \App\Pages\Models\PageVariant $row
     */
@endphp

<div data-column="actions">
    <a class="btn btn-sm btn-success btn-icon-only" href="{{ $row->route->getTargetUrl() }}">
        <i class="fa fa-link"></i>
    </a>

    <a class="btn btn-sm btn-primary btn-icon-only" href="{{ $row->getBackendEditUrl() }}">
        <i class="fa fa-edit"></i>
    </a>
</div>
