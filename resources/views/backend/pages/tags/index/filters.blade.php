@php
    /**
     * @var int[] $languages
     */
@endphp

{{ Form::open([
    'id' => 'tags-filters',
    'class' => 'form-inline',
]) }}

<div class="form-group">
    {{ Form::label('language_id', 'Language:') }}

    {{ Form::select('language_id', $languages, null, [
        'class' => 'custom-select',
    ]) }}
</div>

{{ Form::close() }}
