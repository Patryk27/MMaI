@php
    /** @var \App\Pages\Models\Page $page */
@endphp

<div id="attachments-form" class="tab-pane">
    <div id="upload-attachment-wrapper">
        <button id="upload-attachment-button" class="btn btn-sm btn-primary" type="button">
            <i class="fa fa-upload"></i>
            Upload an attachment
        </button>
    </div>

    @include('backend.components.attachments.table', ['attachments' => $page->attachments])
</div>
