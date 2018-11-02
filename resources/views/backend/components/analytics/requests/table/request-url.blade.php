@php
    /**
     * @var \App\Analytics\Models\Event $row
     */
@endphp

<span data-column="requestUrl">
    <a href="{{ $row->payload['request']['url'] }}">
        {{ str_limit($row->payload['request']['url'], 100) }}
    </a>
</span>
