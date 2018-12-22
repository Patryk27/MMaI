@php
    /**
     * @var \App\Pages\Models\Page $page
     */

    $pagePresenter = $page->getPresenter();
@endphp

<li class="nav-item">
    <a class="nav-link" href="#page" role="tab" data-toggle="tab" tabindex="-1">
        {{ $pagePresenter->getTranslatedType() }}
    </a>
</li>
