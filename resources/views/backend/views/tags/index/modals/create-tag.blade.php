@php
    /**
     * @var \Illuminate\Support\Collection|int[] $websites
     */
@endphp

<div id="create-tag-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Creating new tag
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', '', [
                        'class' => 'form-control',
                    ]) }}
                </div>

                <div class="form-group">
                    {{ Form::label('websiteId', 'Website') }}
                    {{ Form::select('websiteId', $websites, null, [
                        'class' => 'custom-select',
                    ]) }}
                </div>
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
