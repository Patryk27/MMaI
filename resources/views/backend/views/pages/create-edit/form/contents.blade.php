@php
    /**
     * @var \Illuminate\Support\Collection|\App\Tags\Models\Tag[] $tags
     * @var \Illuminate\Support\Collection|\App\Languages\Models\Language[] $languages
     * @var \App\Pages\Models\Page $page
     */
@endphp

<div id="page-section-contents" class="tab-content">
    {{-- Page variants --}}
    @foreach($languages as $language)
        @include('backend.views.pages.create-edit.form.page-variant.content', [
            'tags' => $tags->where('language_id', $language->id)->pluck('name', 'id'),
            'pageVariant' => $page->getVariantForLanguage($language->id) ?? new \App\Pages\Models\PageVariant(),
        ])
    @endforeach

    {{-- Attachments --}}
    @include('backend.views.pages.create-edit.form.attachments.content')

    {{-- Notes --}}
    @include('backend.views.pages.create-edit.form.notes.content')
</div>
