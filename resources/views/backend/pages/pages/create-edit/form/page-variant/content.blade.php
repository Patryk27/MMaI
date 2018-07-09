@php
    /**
     * @var \App\Models\Language $language
     * @var \App\Models\Page $page
     * @var \App\Models\PageVariant|null $pageVariant
     */
@endphp

<div id="form--pageVariant--forLanguage{{ $language->id }}"
     class="tab-pane"
     role="tabpanel"
     data-type="page-variant">
    @include('backend.pages.pages.create-edit.form.page-variant.content-inner')
</div>