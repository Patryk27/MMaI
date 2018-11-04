@php
    /**
     * @var \App\Pages\Models\PageVariant $pageVariant
     * @var \App\Pages\ValueObjects\RenderedPageVariant $renderedPageVariant
     */
@endphp

<article class="page-content">
    {!! $renderedPageVariant->getContent() !!}
</article>
