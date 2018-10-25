@php
    /**
     * @var \App\Tags\Models\Tag $row
     */
@endphp

<div data-column="actions">
    <button type="button" class="btn btn-sm btn-primary btn-icon-only" data-action="edit" data-tag="{{ $row }}">
        <i class="fa fa-edit"></i>
    </button>

    <button type="button" class="btn btn-sm btn-danger btn-icon-only" data-action="delete" data-tag="{{ $row }}">
        <i class="fa fa-trash"></i>
    </button>
</div>
