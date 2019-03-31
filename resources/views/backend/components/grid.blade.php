@php
    /** @var string $name */
@endphp

@include('backend.components.grid.filter.modal')

<div id="{{ $name }}-loader" data-loader-type="tile">
    <div id="{{ $name }}-grid" class="grid"></div>
</div>
