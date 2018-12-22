@php
    /**
     * @var \App\Pages\Models\Page $row
     */

    $presenter = $row->getPresenter();
@endphp

<span class="badge badge-pill {{ $presenter->getStatusBadge() }}" data-column="status">
    {{ __('base/models/page.enums.status.' . $row->status) }}
</span>
