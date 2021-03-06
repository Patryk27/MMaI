@php
    /**
     * @var \App\Pages\Models\Page $page
     */
@endphp

<div class="page-attachments-container">
    <div class="page-attachments">
        <h5 id="!attachments">
            {{ __('frontend/views/pages/show.attachments') }}
        </h5>

        <ul>
            @foreach ($page->attachments->sortBy('name') as $attachment)
                @php
                    $attachmentPresenter = $attachment->getPresenter();
                @endphp

                <li>
                    <a href="{{ $attachmentPresenter->getUrl() }}" rel="nofollow">
                        {{ $attachment->name }}
                    </a>

                    <small class="text-muted">
                        ({{ $attachment->getSizeForHumans() }})
                    </small>
                </li>
            @endforeach
        </ul>
    </div>
</div>
