@php
    /**
     * @var \App\Tags\Models\Tag $row
     */
@endphp

<span data-column="name" data-tag="{{ $row }}">
    {{--<button type="button" class="btn btn-sm btn-icon-only btn-primary"> @todo make this button great again --}}
    {{--<i class="fa fa-edit"></i>--}}
    {{--</button>--}}

    {{ $row->name }}
</span>
