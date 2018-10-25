@php
    /**
     * @var \App\Pages\Models\PageVariant $row
     */
@endphp

<span data-column="type">
    {{ __('base/models/page.enums.type.' . $row->page->type) }}
</span>
