@php
    /**
     * @var \App\Pages\Models\PageVariant $row
     */

    $presenter = $row->getPresenter();
@endphp

<span class="badge badge-pill {{ $presenter->getBadgeForStatus() }}" data-column="status">
    {{ __('base/models/page-variant.enums.status.' . $row->status) }}
</span>
