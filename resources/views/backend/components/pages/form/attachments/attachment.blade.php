@php
    /** @var \App\Attachments\Models\Attachment $attachment */
@endphp

<td data-column="id">
    {{ $attachment->id }}
</td>

<td data-column="name">
    <a class="name">
        {{ $attachment->name }}
    </a>
</td>

<td data-column="mime">
    {{ $attachment->mime }}
</td>

<td data-column="size">
    {{ $attachment->getSizeForHumans() }}
</td>

<td data-column="actions">
    <button type="button" class="btn btn-sm btn-info btn-icon-only" data-action="download">
        <i class="fa fa-download"></i>
    </button>

    <button type="button" class="btn btn-sm btn-primary btn-icon-only" data-action="edit">
        <i class="fa fa-edit"></i>
    </button>

    <button type="button" class="btn btn-sm btn-danger btn-icon-only" data-action="remove">
        <i class="fa fa-trash"></i>
    </button>
</td>
