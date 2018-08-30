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

        <article class="post">
            {{-- Header --}}
            <header>
                <a href="{{ $pageVariant->route->getTargetUrl()  }}">
                    {{ $pageVariant->title }}
                </a>
            </header>

            {{-- Lead --}}
            <section class="lead">
                {!! $post->getLead() !!}
            </section>

            {{-- Footer --}}
            <footer>
                {{-- Link --}}
                <div class="link">
                    <a href="{{ $pageVariant->route->getTargetUrl() }}">
                        Continue reading&nbsp;
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </div>

                @include('frontend.components.post.footer', [
                    'pageVariant' => $pageVariant,
                ])
            </footer>
        </article>
    @endforeach
</div>