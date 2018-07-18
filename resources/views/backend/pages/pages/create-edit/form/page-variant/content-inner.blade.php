@php
    /**
     * @var \App\Models\Language $language
     * @var \App\Models\Page $page
     * @var \App\Models\PageVariant|null $pageVariant
     */
@endphp

<div class="alert alert-info is-enabled-alert">
    This page variant is disabled - select the <code>Enable</code> checkbox to enable it and start working.
</div>

@if(is_null($pageVariant))
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

{{-- Status --}}
<div class="form-group required" data-field="status">
    <label>
        Status
    </label>

    {{ Form::select('status', __('base/models/page-variant.enums.status'), null, [
        'class' => 'form-control',
    ]) }}
</div>

{{-- Route --}}
<div class="form-group">
    <label>
        Route
    </label>

    @php
        $route = '';

        if (isset($pageVariant) && isset($pageVariant->route)) {
            $route = $pageVariant->route->url;
        }
    @endphp

    {{ Form::text('route', $route, [
        'class' => 'form-control',
        'placeholder' => 'my-awesome-page',
    ]) }}
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

@if ($page->isBlogPage())
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