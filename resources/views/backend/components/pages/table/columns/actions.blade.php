@php
    /**
     * @var \App\Pages\Models\Page $row
     */
@endphp

<div data-column="actions">
    @isset($row->route)
        <a class="btn btn-sm btn-success btn-icon-only" href="{{ $row->route->getAbsoluteUrl() }}">
            <i class="fa fa-link"></i>
        </a>
    @endisset

    <a class="btn btn-sm btn-primary btn-icon-only" href="{{ $row->getEditUrl() }}">
        <i class="fa fa-edit"></i>
    </a>
</div>
