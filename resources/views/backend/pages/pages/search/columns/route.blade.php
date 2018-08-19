@php
    /**
     * @var \App\Pages\Models\PageVariant $row
     */
@endphp

@isset ($row->route)
    <span title="{{ $row->route->getTargetUrl() }}">
        {{ $row->route->url }}
    </span>
@else
    -
@endisset