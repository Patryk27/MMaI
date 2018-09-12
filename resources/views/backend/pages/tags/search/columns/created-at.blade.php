@php
    /**
     * @var \App\Tags\Models\Tag $row
     */
@endphp

<span data-column="created-at" title="{{ $row->created_at->diffForHumans() }}">
    {{ $row->created_at->format('Y-m-d') }}
</span>
