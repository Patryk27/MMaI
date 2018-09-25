@php
    /**
     * @var \Illuminate\Support\Collection|\App\Languages\Models\Language[] $languages
     */
@endphp

<ul id="page-section-tabs" class="nav nav-tabs" role="tablist">
    {{-- Page variants --}}
    @foreach($languages as $language)
        @include('backend.pages.pages.create-edit.form.page-variant.tab')
    @endforeach

    {{-- Attachments --}}
    @include('backend.pages.pages.create-edit.form.attachments.tab')

    {{-- Notes --}}
    @include('backend.pages.pages.create-edit.form.notes.tab')
</ul>
