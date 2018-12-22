@php
    /**
     * @var \App\Pages\ValueObjects\RenderedPage $renderedPage
     */
@endphp

<article class="page-content">
    {!! $renderedPage->getContent() !!}
</article>
