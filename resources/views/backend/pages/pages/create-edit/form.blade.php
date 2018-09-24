@php
    /**
     * @var \Illuminate\Support\Collection|\App\Tags\Models\Tag[] $tags
     * @var \Illuminate\Support\Collection|\App\Languages\Models\Language[] $languages
     * @var \App\Pages\Models\Page $page
     */
@endphp

{{-- Section tabs --}}
<ul id="page-section-tabs" class="nav nav-tabs" role="tablist">
    @foreach($languages as $language)
        @include('backend.pages.pages.create-edit.form.page-variant.tab')
    @endforeach

    @include('backend.pages.pages.create-edit.form.attachments.tab')
</ul>

{{-- Section contents --}}
<div id="page-section-contents" class="tab-content">
    @foreach($languages as $language)
        @include('backend.pages.pages.create-edit.form.page-variant.content', [
            'tags' => $tags->where('language_id', $language->id)->pluck('name', 'id'),
            'pageVariant' => $page->getVariantForLanguage($language->id) ?? new \App\Pages\Models\PageVariant(),
        ])
    @endforeach

    @include('backend.pages.pages.create-edit.form.attachments.content')
</div>

{{-- Form's footer --}}
<div id="page-form-footer">
    <button id="page-form-submit-button" class="btn btn-success" type="button">
        Save
    </button>
</div>
