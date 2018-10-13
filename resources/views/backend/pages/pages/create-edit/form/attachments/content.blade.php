@php
    /**
     * @var \App\Pages\Models\Page $page
     */
@endphp

<div id="page-attachments" data-section-type="attachments" class="tab-pane" role="tabpanel">
    {{-- Add an attachment --}}
    <div id="add-attachment-wrapper">
        <button id="add-attachment-button" type="button" class="btn btn-sm btn-primary">
            <i class="fa fa-upload"></i>
            Add an attachment
        </button>
    </div>

    {{-- Attachments --}}
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
        @foreach ($page->attachments as $attachment)
            @php
                $attachmentPresenter = $attachment->getPresenter();
            @endphp

            <tr data-attachment="{{ $attachment }}" data-attachment-url="{{ $attachmentPresenter->getUrl() }}">
                @include('backend.pages.pages.create-edit.form.attachments.content.attachment')
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        <tr class="template">
            @include('backend.pages.pages.create-edit.form.attachments.content.attachment', [
                'attachment' => new \App\Attachments\Models\Attachment(),
            ])
        </tr>
        </tfoot>
    </table>
</div>
