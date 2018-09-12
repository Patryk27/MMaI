@php
    /**
     * @var \Illuminate\Support\Collection|int[] $languages
     */
@endphp

{{ Form::open([
    'id' => 'tags-search-form',
    'class' => 'form-inline',
]) }}

<div class="form-group">
    {{ Form::label('language_id', 'Language:') }}

    {{ Form::select('language_id', $languages, null, [
        'class' => 'custom-select',
    ]) }}
</div>

{{ Form::close() }}
