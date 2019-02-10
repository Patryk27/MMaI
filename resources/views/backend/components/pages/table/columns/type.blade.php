@php
    /**
     * @var \App\Pages\Models\Page $row
     */
@endphp

<span data-column="type">
    {{ __('base/models/page.enums.type.' . $row->type) }}
</span>
