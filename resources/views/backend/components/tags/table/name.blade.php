@php
    /**
     * @var \App\Tags\Models\Tag $row
     */
@endphp

<span data-column="name" data-tag="{{ $row }}">
    {{ $row->name }}
</span>
