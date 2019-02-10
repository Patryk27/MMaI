@php
    /**
     * @var \App\Tags\Models\Tag $row
     */
@endphp

<div data-column="actions">
    <button type="button" class="btn btn-sm btn-primary btn-icon-only" data-tag="{{ $row }}" data-action="edit">
        <i class="fa fa-edit"></i>
    </button>

    <button type="button" class="btn btn-sm btn-danger btn-icon-only" data-tag="{{ $row }}" data-action="delete">
        <i class="fa fa-trash"></i>
    </button>
</div>
