@php
    /** @var \Illuminate\Support\Collection|\App\Attachments\Models\Attachment[] $attachments */
@endphp

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
    @foreach ($attachments->sortBy('name') as $attachment)
        <tr data-attachment="{{ $attachment }}">
            @include('backend.components.attachments.table.row')
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr class="template">
        @include('backend.components.attachments.table.row', [
            'attachment' => new \App\Attachments\Models\Attachment(),
        ])
    </tr>
    </tfoot>
</table>
