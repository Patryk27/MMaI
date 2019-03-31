@php
    /** @var string $id */
    /** @var string $title */
    /** @var string $body */
@endphp

<div id="{{ $id }}" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ $title }}
                </h5>
            </div>

            <div class="modal-body">
                {{ $body }}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-close" data-dismiss="modal">
                    Cancel
                </button>

                <button type="submit" class="btn btn-primary btn-submit">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
