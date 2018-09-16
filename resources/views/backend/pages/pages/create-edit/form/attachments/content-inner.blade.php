@php
    /**
     * @var \App\Pages\Models\Page $page
     */
@endphp

{{-- Add an attachment --}}
<div id="add-attachment-wrapper">
    <button id="add-attachment-button" type="button" class="btn btn-sm btn-success">
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
        <th>Size</th>
        <th>&nbsp;</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($page->attachments as $attachment)
        <tr data-attachment="{{ $attachment }}">
            @include('backend.pages.pages.create-edit.form.attachments.content-inner.attachment')
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr class="template">
        @include('backend.pages.pages.create-edit.form.attachments.content-inner.attachment', [
            'attachment' => new \App\Attachments\Models\Attachment(),
        ])
    </tr>
    </tfoot>
</table>
