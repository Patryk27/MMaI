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

    <table id="attachments-table" class="table table-striped table-dark">
        <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>MIME type</th>
            <th>Size</th>
            <th>&nbsp;</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($page->attachments->sortBy('name') as $attachment)
            @php
                /** @var \App\Attachments\Models\Attachment $attachment */
                $attachmentPresenter = $attachment->getPresenter();
            @endphp

            <tr data-attachment="{{ $attachment }}" data-attachment-url="{{ $attachmentPresenter->getUrl() }}">
                @include('backend.components.pages.form.attachments.attachment')
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        <tr class="template">
            @include('backend.components.pages.form.attachments.attachment', [
                'attachment' => new \App\Attachments\Models\Attachment(),
            ])
        </tr>
        </tfoot>
    </table>
</div>
