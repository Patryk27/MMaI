@php
    /**
     * @var \App\Tags\Models\Tag $row
     */
@endphp

<div class="dropdown dropleft show float-right">
    <a class="btn btn-primary btn-sm dropdown-toggle"
       data-toggle="dropdown">

    </a>

    <div class="dropdown-menu">
        <button class="dropdown-item btn-tag-action" data-action="edit" data-tag="{{ $row }}">
            <i class="fa fa-edit"></i>&nbsp;
            Edit
        </button>

        <button class="dropdown-item btn-tag-action" data-action="delete" data-tag="{{ $row }}">
            <i class="fa fa-trash"></i>&nbsp;
            Delete
        </button>
    </div>
</div>