@php
    /**
     * @var \Illuminate\Support\Collection|\App\Languages\Models\Language[] $languages
     * @var \App\Pages\Models\Page $page
     */
@endphp

<div class="form-messages"></div>

{{-- Tabs' titles --}}
<ul class="form-navigation-tabs nav nav-tabs" role="tablist">
    @foreach($languages as $language)
        @include('backend.pages.pages.create-edit.form.page-variant.tab', [
            'language' => $language,
        ])
    @endforeach

    @include('backend.pages.pages.create-edit.form.media-library.tab')
</ul>

{{-- Tabs' contents --}}
<div class="form-navigation-contents tab-content">
    @foreach($languages as $language)
        @include('backend.pages.pages.create-edit.form.page-variant.content', [
            'language' => $language,
            'pageVariant' => $page->getPageVariantForLanguage($language->id),
        ])
    @endforeach

    @include('backend.pages.pages.create-edit.form.media-library.content')
</div>

{{-- Form's footer --}}
<div class="form-footer">
    <button type="submit" class="btn btn-primary">
        Save
    </button>
</div>