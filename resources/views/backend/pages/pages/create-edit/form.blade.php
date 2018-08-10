@php
    /**
     * @var \Illuminate\Support\Collection|\App\Languages\Models\Language[] $languages
     * @var \App\Pages\Models\Page $page
     */
@endphp

{{-- Section tabs --}}
<ul id="page-section-tabs" class="nav nav-tabs" role="tablist">
    @foreach($languages as $language)
        @include('backend.pages.pages.create-edit.form.page-variant.tab', [
            'language' => $language,
        ])
    @endforeach

    @include('backend.pages.pages.create-edit.form.media-library.tab')
</ul>

{{-- Section contents --}}
<div id="page-section-contents" class="tab-content">
    @foreach($languages as $language)
        @include('backend.pages.pages.create-edit.form.page-variant.content', [
            'language' => $language,
            'pageVariant' => $page->getPageVariantForLanguage($language->id),
        ])
    @endforeach

    @include('backend.pages.pages.create-edit.form.media-library.content')
</div>

{{-- Form's footer --}}
<div id="page-form-footer">
    <button id="page-form-submit-button" class="btn btn-primary" type="button">
        Save
    </button>
</div>