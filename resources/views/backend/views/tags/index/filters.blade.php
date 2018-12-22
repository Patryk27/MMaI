@php
    /**
     * @var int[] $languages
     */
@endphp

{{ Form::open([
    'id' => 'tags-filters',
]) }}

<div class="row w-100">
    <div class="col-xs-12 col-sm-6 col-md-2 form-group">
        {{ Form::label('language_ids[]', 'Language') }}
        {{ Form::select('language_ids[]', $languages, null, [
            'class' => 'custom-select select2',
            'multiple' => 'multiple',
        ]) }}
    </div>
</div>

{{ Form::close() }}
