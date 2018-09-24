@php
    /**
     * @var \App\Pages\ValueObjects\RenderedPageVariant $renderedPageVariant
     */
@endphp

<article class="page-content">
    {!! $renderedPageVariant->getContent() !!}
</article>
