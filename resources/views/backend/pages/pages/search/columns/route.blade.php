@php
    /**
     * @var \App\Models\PageVariant $row
     */
@endphp

@isset ($row->route)
    {{ $row->route->url }}
@else
    -
@endisset