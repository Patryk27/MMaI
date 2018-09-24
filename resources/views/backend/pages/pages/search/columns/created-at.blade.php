@php
    /**
     * @var \App\Pages\Models\PageVariant $row
     */
@endphp

<span data-column="created-at" title="{{ $row->created_at->format('Y-m-d') }}">
    {{ $row->created_at->diffForHumans() }}
</span>
