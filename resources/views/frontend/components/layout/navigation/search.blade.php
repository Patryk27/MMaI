<div class="nav-section nav-search">
    <form method="get" action="/!search">
        <div class="input-group">
            {{ Form::search('query', '', [
                'class' => 'form-control input-light',
                'placeholder' => __('frontend/layout/navigation.search') . '...',
                'aria-label' => __('frontend/layout/navigation.search'),
            ]) }}

            <div class="input-group-append">
                <button type="submit" class="btn btn-light btn-icon-only">
                    <i class="fa fa-search"></i>
                    <span class="sr-only">
                        {{ __('frontend/layout/navigation.search') }}
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
