@component('backend.components.core.form-modal', ['id' => 'grid-filter-modal'])
    @slot('title')
        {{-- filled by JS --}}
    @endslot

    @slot('body')
        @include('backend.components.grid.filter.form')
    @endslot
@endcomponent
