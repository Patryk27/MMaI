@php
    /**
     * @var \Illuminate\Support\Collection|int[] $languages
     */
@endphp

<div id="create-tag-modal" class="modal" role="dialog">
    <div class="modal-dialog" role="document">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Creating a tag
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>

            <div class="modal-body">
                {{-- Tag name --}}
                <div class="form-group">
                    {{ Form::label('name', 'Tag\'s name') }}

                    {{ Form::text('name', '', [
                        'class' => 'form-control',
                    ]) }}
                </div>

                {{-- Tag language --}}
                <div class="form-group">
                    {{ Form::label('language_id', 'Tag\'s language') }}

                    {{ Form::select('language_id', $languages, null, [
                        'class' => 'custom-select',
                    ]) }}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">
                    Close
                </button>

                <button type="submit" class="btn btn-primary btn-submit">
                    Create tag
                </button>
            </div>
        </form>
    </div>
</div>