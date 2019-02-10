@php
    /**
     * @var \App\Tags\Models\Tag $row
     */
@endphp

<span data-column="name" data-tag="{{ $row }}" data-action="edit">
    {{ $row->name }}
</span>
