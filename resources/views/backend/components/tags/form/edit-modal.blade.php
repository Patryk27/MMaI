@component('backend.components.core.form-modal', ['id' => 'edit-tag-modal'])
    @slot('title')
        Editing tag
    @endslot

    @slot('body')
        @include('backend.components.tags.form.edit')
    @endslot
@endcomponent
