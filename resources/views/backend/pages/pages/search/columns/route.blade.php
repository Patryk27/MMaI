@php
    /**
     * @var \App\Pages\Models\PageVariant $row
     */
@endphp

@isset ($row->route)
    {{ $row->route->url }}
@else
    -
@endisset