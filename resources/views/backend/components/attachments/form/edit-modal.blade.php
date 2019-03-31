@component('backend.components.core.form-modal', ['id' => 'edit-attachment-modal'])
    @slot('title')
        Editing attachment
    @endslot

    @slot('body')
        @include('backend.components.attachments.form.edit')
    @endslot
@endcomponent
