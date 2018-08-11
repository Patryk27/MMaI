@php
    /**
     * @var \App\Languages\Models\Language $language
     */
@endphp

<div id="form--pageVariant--forLanguage{{ $language->id }}"
     class="tab-pane"
     data-section-type="page-variant"
     role="tabpanel">
    @include('backend.pages.pages.create-edit.form.page-variant.content-inner')
</div>