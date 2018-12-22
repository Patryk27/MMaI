{{-- @todo type-hint --}}

@if($renderedPages->isEmpty())
    <div class="alert alert-warning">
        {{ __('frontend/views/search.no-posts-found') }}
    </div>
@else
    <div class="alert alert-info">
        {{ __('frontend/views/search.found-n-posts', ['n' => $renderedPages->count()]) }}
    </div>

    @include('frontend.components.pages.list')
@endif
