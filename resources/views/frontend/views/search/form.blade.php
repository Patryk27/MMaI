<form id="search-form" method="get" action="#">
    <div class="input-group">
        {{ Form::search('query', $query, [
            'class' => 'form-control input-light',
            'placeholder' => __('frontend/views/search.action') . '...',
            'aria-label' => __('frontend/views/search.action'),
            'autofocus' => true,
        ]) }}

        <div class="input-group-append">
            <button type="submit" class="btn btn-light btn-icon-only">
                <i class="fa fa-search"></i>
                <span class="sr-only">
                    {{ __('frontend/views/search.action') }}
                </span>
            </button>
        </div>
    </div>
</form>
