@php
    /**
     * @var \App\Languages\Models\Language $language
     */
@endphp

<li class="nav-item">
    <a class="nav-link"
       href="#form--pageVariant--forLanguage{{ $language->id }}"
       role="tab"
       data-toggle="tab">
        {{ $language->english_name }}
    </a>
</li>