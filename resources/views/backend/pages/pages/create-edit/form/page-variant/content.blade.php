@php
    /**
     * @var \Illuminate\Support\Collection|\App\Tags\Models\Tag[] $tags
     * @var \App\Languages\Models\Language $language
     * @var \App\Pages\Models\Page $page
     * @var \App\Pages\Models\PageVariant $pageVariant
     */
@endphp

<div id="page-variant-{{ $language->id }}" data-section-type="page-variant" class="tab-pane" role="tabpanel">
    <div class="alert alert-info is-disabled-alert">
        This page variant is disabled - select the <code>Enable</code> checkbox to enable it and start working.
    </div>

    @if(!$pageVariant->exists)
        {{-- Enabled --}}
        <div class="form-check is-enabled-checkbox">
            {{ Form::checkbox('is_enabled', 1, null, [
                'class' => 'form-check-input',
            ]) }}

            <label class="form-check-label">
                Enabled
            </label>
        </div>
    @endif

    {{ Form::model($pageVariant) }}

    {{ Form::hidden('id') }}
    {{ Form::hidden('language_id', $language->id) }}

    {{-- URL --}}
    <div class="form-group">
        <label>
            URL
        </label>

        @php
            $url = '';

            if (isset($pageVariant->route)) {
                $url = $pageVariant->route->url;
            }
        @endphp

        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    {{ env('APP_PROTOCOL') }}://{{ $language->slug }}.{{ env('APP_DOMAIN') }}/
                </div>
            </div>

            {{ Form::text('url', $url, [
                'class' => 'form-control',
                'placeholder' => 'my-awesome-page',
            ]) }}

            @if(strlen($url) > 0)
                <div class="input-group-append">
                    <a class="btn btn-secondary btn-icon-only"
                       href="{{ $pageVariant->route->getTargetUrl() }}"
                       title="Navigate to the page">
                        <i class="fa fa-external-link-alt"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Title --}}
    <div class="form-group required">
        <label>
            Title
        </label>

        {{ Form::text('title', null, [
            'class' => 'form-control',
            'placeholder' => 'My Awesome Page',
        ]) }}
    </div>

    {{-- Tags --}}
    <div class="form-group">
        <label>
            Tags
        </label>

        {{ Form::select('tag_ids', $tags, $pageVariant->tags->pluck('id'), [
            'class' => 'custom-select select2',
            'multiple' => 'multiple',
        ]) }}
    </div>

    {{-- Status --}}
    <div class="form-group required" data-field="status">
        <label>
            Status
        </label>

        {{ Form::select('status', __('base/models/page-variant.enums.status'), null, [
            'class' => 'custom-select',
        ]) }}
    </div>

    @if ($page->isBlogPost())
        {{-- Lead --}}
        <div class="form-group">
            <label>
                Lead
            </label>

            {{ Form::textarea('lead', null, [
                'class' => 'form-control',
                'placeholder' => 'This is my awesome page!',
            ]) }}
        </div>
    @endif

    {{-- Content --}}
    <div class="form-group">
        <label>
            Content
        </label>

        {{ Form::textarea('content', null, [
            'class' => 'form-control',
            'placeholder' => '# This is my awesome page!',
        ]) }}
    </div>

    {{ Form::close() }}
</div>
