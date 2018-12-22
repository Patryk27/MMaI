@php
    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Pages\ValueObjects\RenderedPage[] $renderedPages
     */
@endphp

{{-- @todo rename to "pages-list" --}}
<div class="posts-list">
    @foreach($renderedPages as $renderedPage)
        @php
            $page = $renderedPage->getPage();
        @endphp

        <article class="post-item">
            {{-- Header --}}
            <header class="post-header">
                <a href="{{ $page->route->getEntireUrl()  }}">
                    {{ $page->title }}
                </a>
            </header>

            {{-- Lead --}}
            <section class="post-lead">
                {!! $renderedPage->getLead() !!}
            </section>

            {{-- Footer --}}
            <footer class="post-footer">
                {{-- Tags --}}
                @if($page->tags->isNotEmpty())
                    <div class="post-footer-tags">
                        @foreach($page->tags as $tag)
                            <a class="post-footer-tag" href="/!search?query={{ $tag->name }}">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Published at --}}
                <div class="post-footer-published-at">
                    {{ $page->published_at->format(config('mmai.posts.date-format')) }}
                </div>
            </footer>
        </article>
    @endforeach
</div>
