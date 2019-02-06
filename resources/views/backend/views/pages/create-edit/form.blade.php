@php
    /**
     * @var \App\Pages\Models\Page $page
     */

    $pagePresenter = $page->getPresenter();
@endphp

<ul id="form-tabs" class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" href="#page-form" role="tab" tabindex="-1" data-toggle="tab">
            {{ $pagePresenter->getTranslatedType() }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#attachments-form" role="tab" tabindex="-1" data-toggle="tab">
            Attachments
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#notes-form" role="tab" tabindex="-1" data-toggle="tab">
            Notes
        </a>
    </li>
</ul>

<div id="form-contents" class="tab-content">
    @include('backend.views.pages.create-edit.form.page')
    @include('backend.views.pages.create-edit.form.attachments')
    @include('backend.views.pages.create-edit.form.notes')
</div>

<div id="form-footer">
    <button id="form-submit" class="btn btn-success">
        Save
    </button>
</div>
