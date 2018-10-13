@php
    /**
     * @var \App\Pages\Models\Page $page
     */
@endphp

{{ Form::hidden('page_type', $page->type) }}

@include('backend.pages.pages.create-edit.form.tabs')
@include('backend.pages.pages.create-edit.form.contents')
@include('backend.pages.pages.create-edit.form.footer')
