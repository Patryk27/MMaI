@php
    /**
     * @var \Illuminate\Support\Collection|\App\Tags\Models\Tag[] $tags
     * @var \App\Pages\Models\Page $page
     */
@endphp

<div id="page" data-section-type="page" class="tab-pane" role="tabpanel">
    {{ Form::model($page) }}
    {{ Form::hidden('id') }}

    <div class="row">
        {{-- URL --}}
        <div class="col-xs-12 col-md-6 form-group">
            <label>
                URL
            </label>

            @php
                $url = '';

                if (isset($page->route)) {
                    $url = $page->route->url;
                }
            @endphp

            <div class="input-group">
                {{ Form::text('url', $url, [
                    'class' => 'form-control',
                ]) }}

                @if(strlen($url) > 0)
                    <div class="input-group-append">
                        <a class="btn btn-primary btn-icon-only"
                           href="{{ $page->route->getEntireUrl() }}"
                           title="Open this page">
                            <i class="fa fa-link"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Status --}}
        <div class="col-xs-12 col-md-6 form-group required" data-field="status">
            <label>
                Status
            </label>

            {{ Form::select('status', __('base/models/page.enums.status'), null, [
                'class' => 'custom-select',
            ]) }}
        </div>

        {{-- Title --}}
        <div class="col-xs-12 col-md-6 form-group required">
            <label>
                Title
            </label>

            {{ Form::text('title', null, [
                'class' => 'form-control',
            ]) }}
        </div>

        {{-- Tags --}}
        <div class="col-xs-12 col-md-6 form-group">
            <label>
                Tags
            </label>

            {{ Form::select('tag_ids', $tags, $page->tags->pluck('id'), [
                'class' => 'custom-select select2',
                'multiple' => 'multiple',
            ]) }}
        </div>

        @if ($page->isPost())
            {{-- Lead --}}
            <div class="col-xs-12 col-md-12 form-group">
                <label>
                    Lead
                </label>

                {{ Form::textarea('lead', null, [
                    'class' => 'form-control',
                ]) }}
            </div>
        @endif

        {{-- Content --}}
        <div class="col-xs-12 col-md-12 form-group">
            <label>
                Content
            </label>

            {{ Form::textarea('content', null, [
                'class' => 'form-control',
            ]) }}
        </div>
    </div>

    {{ Form::close() }}
</div>
