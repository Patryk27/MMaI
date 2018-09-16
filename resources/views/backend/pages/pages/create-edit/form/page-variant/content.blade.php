@php
    /**
     * @var \App\Languages\Models\Language $language
     */
@endphp

<div id="form--pageVariant--forLanguage{{ $language->id }}"
     data-section-type="page-variant"
     class="tab-pane"
     role="tabpanel">
    @include('backend.pages.pages.create-edit.form.page-variant.content-inner')
</div>
