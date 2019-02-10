@component('backend.components.core.generic-modal-form', ['id' => 'create-tag-modal'])
    @slot('title')
        Creating tag
    @endslot

    @slot('body')
        @include('backend.components.tags.form.create')
    @endslot
@endcomponent
