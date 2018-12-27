@php
    /**
     * @var \Illuminate\Support\Collection|\App\Websites\Models\Website[] $websites
     * @var \App\Pages\Models\Page $page
     */
@endphp

<div id="page" data-section-type="page" class="tab-pane" role="tabpanel">
    {{ Form::model($page) }}
    {{ Form::hidden('id') }}
    {{ Form::hidden('type') }}

    <div class="row">
        {{-- Website --}}
        <div class="col-xs-12 col-md-3 form-group required">
            {{ Form::label('website_id', 'Website') }}
            {{ Form::select('website_id', $websites->pluck('name', 'id'), null, [
                'class' => 'custom-select',
            ]) }}
        </div>

        {{-- URL --}}
        <div class="col-xs-12 col-md-6 form-group">
            {{ Form::label('url', 'URL') }}

            <div class="input-group">
                {{ Form::text('url', isset($page->route) ? $page->route->url : '', [
                    'class' => 'form-control',
                ]) }}

                @isset($page->route)
                    <div class="input-group-append">
                        <a class="btn btn-primary btn-icon-only"
                           href="{{ $page->route->getAbsoluteUrl() }}"
                           title="Open this page">
                            <i class="fa fa-link"></i>
                        </a>
                    </div>
                @endisset
            </div>
        </div>

        {{-- Status --}}
        <div class="col-xs-12 col-md-3 form-group required" data-field="status">
            {{ Form::label('status', 'Status') }}
            {{ Form::select('status', __('base/models/page.enums.status'), null, [
                'class' => 'custom-select',
            ]) }}
        </div>

        {{-- Title --}}
        <div class="col-xs-12 col-md-6 form-group required">
            {{ Form::label('title', 'Title') }}
            {{ Form::text('title', null, [
                'class' => 'form-control',
            ]) }}
        </div>

        {{-- Tags --}}
        <div class="col-xs-12 col-md-6 form-group">
            {{ Form::label('tag_ids', 'Tags') }}
            {{ Form::select('tag_ids', [], $page->tags->pluck('id'), [
                'class' => 'custom-select select2',
                'multiple' => 'multiple',
            ]) }}
        </div>

        @if ($page->isPost())
            {{-- Lead --}}
            <div class="col-xs-12 col-md-12 form-group">
                {{ Form::label('lead', 'Lead') }}
                {{ Form::textarea('lead', null, [
                    'class' => 'form-control',
                ]) }}
            </div>
        @endif

        {{-- Content --}}
        <div class="col-xs-12 col-md-12 form-group">
            {{ Form::label('content', 'Content') }}
            {{ Form::textarea('content', null, [
                'class' => 'form-control',
            ]) }}
        </div>
    </div>

    {{ Form::close() }}
</div>
