@php
    /**
     * @var \App\Analytics\Models\Event $row
     */
@endphp

<span data-column="url">
    <a href="{{ $row->payload['url'] }}">
        {{ str_limit($row->payload['url'], 100) }}
    </a>
</span>
