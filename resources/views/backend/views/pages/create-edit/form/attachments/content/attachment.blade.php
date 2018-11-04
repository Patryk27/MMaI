@php
    /**
     * @var \App\Attachments\Models\Attachment $attachment
     */

    $attachmentPresenter = $attachment->getPresenter();
@endphp

<td data-column="id">
    {{ $attachment->id }}
</td>

<td data-column="name">
    <a href="{{ $attachmentPresenter->getUrl() }}" class="name" data-action="download">
        {{ $attachment->name }}
    </a>

    @if(!$attachment->exists)
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
    @endif
</td>

<td data-column="mime">
    {{ $attachment->mime }}
</td>

<td data-column="size">
    {{ $attachment->getSizeForHumans() }}
</td>

<td data-column="actions">
    <button type="button" class="btn btn-sm btn-primary btn-icon-only" data-action="copy-url"
            title="Copy attachment's URL to the clipboard.">
        <i class="fa fa-copy"></i>
    </button>

    <button type="button" class="btn btn-sm btn-danger btn-icon-only" data-action="delete"
            title="Delete attachment">
        <i class="fa fa-trash"></i>
    </button>
</td>
