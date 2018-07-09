@php
    /**
     * @var \App\Models\PageVariant $row
     */
@endphp

<div class="dropdown dropleft show float-right">
    <a class="btn btn-primary btn-sm dropdown-toggle"
       data-toggle="dropdown">

    </a>

    <div class="dropdown-menu">
        @isset($row->route)
            <a class="dropdown-item" href="{{ '/' . $row->route->url }}">
                <i class="fa fa-link"></i>&nbsp;
                Show
            </a>
        @endisset

        <a class="dropdown-item" href="{{ $row->getBackendEditUrl() }}">
            <i class="fa fa-edit"></i>&nbsp;
            Edit
        </a>
    </div>
</div>