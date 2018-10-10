@php
    /**
     * @var \App\Pages\Models\Page $page
     */
@endphp

<div class="page-attachments-container">
    <div class="page-attachments">
        <h5 id="!attachments">
            Attachments {{-- @todo translation --}}
        </h5>

        <ul>
            @foreach ($page->attachments as $attachment)
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
