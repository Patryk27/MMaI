@component('backend.components.core.generic-modal-form', ['id' => 'edit-tag-modal'])
    @slot('title')
        Editing tag
    @endslot

    @slot('body')
        @include('backend.components.tags.form.edit')
    @endslot
@endcomponent
