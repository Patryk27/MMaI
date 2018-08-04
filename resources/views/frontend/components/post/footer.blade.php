@php
    /**
     * @var \App\Pages\Models\PageVariant $pageVariant
     */
@endphp

<div class="post-footer">
    {{-- Tags --}}
    @if($pageVariant->tags->isNotEmpty())
        <div class="tags">
            <i class="fas fa-tags"></i>

            @foreach($pageVariant->tags as $tag)
                <a class="tag" href="@todo">
                    {{ $tag->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- Published at --}}
    <div class="date">
        <i class="fas fa-calendar"></i>

        {{ $pageVariant->published_at->format(config('mmai.posts.date-format')) }}
    </div>
</div>