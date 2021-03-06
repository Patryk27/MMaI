@php
    /** @var \App\Pages\Models\Page $page */
@endphp

<div id="notes-form" class="tab-pane" role="tabpanel">
    <div class="form-group">
        {{ Form::textarea('notes', $page->notes, [
            'class' => 'form-control',
            'placeholder' => 'Your private notes about this post / page.'
        ]) }}
    </div>
</div>
