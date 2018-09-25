@php
    /**
     * @var \App\Attachments\Models\Attachment $attachment
     */
@endphp

<td data-column="id">
    {{ $attachment->id }}
</td>

<td data-column="name">
    <a class="name" href="#" data-action="show">
        {{ $attachment->name }}
    </a>

    @if(!$attachment->exists)
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
    @endif
</td>

<td data-column="size">
    {{ $attachment->getSizeForHumans() }}
</td>

<td data-column="actions">
    <button type="button" class="btn btn-sm btn-primary btn-icon-only" data-action="show">
        <i class="fa fa-external-link-alt"></i>
    </button>

    <button type="button" class="btn btn-sm btn-danger btn-icon-only" data-action="delete">
        <i class="fa fa-trash"></i>
    </button>
</td>
