@php
    /**
     * @var \App\Pages\Models\Page $page
     */
@endphp

<div id="page-notes" data-section-type="notes" class="tab-pane" role="tabpanel">
    <div class="form-group">
        {{ Form::textarea('notes', $page->notes, [
            'class' => 'form-control',
            'placeholder' => 'Your own private notes about this page.'
        ]) }}
    </div>
</div>
