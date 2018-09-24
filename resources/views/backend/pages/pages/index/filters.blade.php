@php
    /**
     * @var string[] $types
     * @var int[] $languages
     * @var string[] $statuses
     */
@endphp

{{ Form::open([
    'id' => 'pages-filters',
]) }}

<div class="row">
    {{-- Type --}}
    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
        {{ Form::label('types[]', 'Type:') }}

        {{ Form::select('types[]', $types, null, [
            'class' => 'custom-select select2',
            'multiple' => 'multiple',
        ]) }}
    </div>

    {{-- Url --}}
    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
        {{ Form::label('url', 'URL:') }}

        {{ Form::text('url', null, [
            'class' => 'form-control',
        ]) }}
    </div>

    {{-- Language --}}
    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
        {{ Form::label('language_ids[]', 'Language:') }}

        {{ Form::select('language_ids[]', $languages, null, [
            'class' => 'custom-select select2',
            'multiple' => 'multiple',
        ]) }}
    </div>

    {{-- Status --}}
    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
        {{ Form::label('statuses[]', 'Status:') }}

        {{ Form::select('statuses[]', $statuses, null, [
            'class' => 'custom-select select2',
            'multiple' => 'multiple',
        ]) }}
    </div>
</div>

{{ Form::close() }}
