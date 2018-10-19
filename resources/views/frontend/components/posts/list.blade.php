@php
    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\Pages\ValueObjects\RenderedPageVariant[] $posts
     */
@endphp

<div class="posts-list">
    @if($posts->isEmpty())
        <p class="no-posts-alert text-muted">
            There are no posts here.
        </p>
    @endif

    @foreach($posts as $post)
        @php
            /**
             * @var \App\Pages\ValueObjects\RenderedPageVariant $post
             */

            $pageVariant = $post->getPageVariant();
        @endphp

        <article class="post-item">
            {{-- Header --}}
            <header class="post-header">
                <a href="{{ $pageVariant->route->getTargetUrl()  }}">
                    {{ $pageVariant->title }}
                </a>
            </header>

            {{-- Lead --}}
            <section class="post-lead">
                {!! $post->getLead() !!}
            </section>

            {{-- Footer --}}
            <footer class="post-footer">
                {{-- Tags --}}
                @if($pageVariant->tags->isNotEmpty())
                    <div class="post-footer-tags">
                        @foreach($pageVariant->tags as $tag)
                            <a class="post-footer-tag" href="/!search?query={{ $tag->name }}">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Published at --}}
                <div class="post-footer-published-at">
                    {{ $pageVariant->published_at->format(config('mmai.posts.date-format')) }}
                </div>
            </footer>
        </article>
    @endforeach
</div>
