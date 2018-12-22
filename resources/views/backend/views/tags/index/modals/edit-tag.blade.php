<div id="edit-tag-modal" class="modal" role="dialog">
    <div class="modal-dialog" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Editing tag
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
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-submit">
                    Save
                </button>

                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
