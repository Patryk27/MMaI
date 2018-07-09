@php
    /**
     * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\App\ValueObjects\RenderedPageVariant[] $renderedPageVariants
     */
@endphp

@include('frontend.components.posts.list', [
    'posts' => $renderedPageVariants,
])

{{ $renderedPageVariants->links() }}