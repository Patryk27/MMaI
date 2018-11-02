@php
    /**
     * @var \App\Analytics\Models\Event $row
     */

    $statusCode = $row->payload['response']['statusCode'];

    // Determine the badge
    $badgeClass = 'primary';

    switch (true) {
        case $statusCode >= 200 && $statusCode < 300:
            $badgeClass = 'success';
            break;

        case $statusCode >= 300 && $statusCode < 400:
            $badgeClass = 'info';
            break;

        case $statusCode >= 400 && $statusCode < 500:
            $badgeClass = 'danger';
            break;
    }
@endphp

<span data-column="responseCode">
    <span class="badge badge-{{ $badgeClass }}">
        {{ $statusCode }}
    </span>
</span>
