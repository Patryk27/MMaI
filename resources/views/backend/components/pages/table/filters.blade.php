@php
    /** @var string[] $types */
    /** @var string[] $statuses */
    /** @var int[] $websites */
@endphp

{{ Form::open(['id' => 'pages-filters']) }}

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-2 form-group">
        {{ Form::label('website_ids[]', 'Website') }}
        {{ Form::select('website_ids[]', $websites, null, ['class' => 'custom-select select2', 'multiple' => 'multiple']) }}
    </div>

    <div class="col-xs-12 col-sm-6 col-md-2 form-group">
        {{ Form::label('types[]', 'Type') }}
        {{ Form::select('types[]', $types, null, ['class' => 'custom-select select2', 'multiple' => 'multiple']) }}
    </div>

    <div class="col-xs-12 col-sm-6 col-md-2 form-group">
        {{ Form::label('statuses[]', 'Status') }}
        {{ Form::select('statuses[]', $statuses, null, ['class' => 'custom-select select2', 'multiple' => 'multiple']) }}
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 form-group">
        {{ Form::label('url', 'URL') }}
        {{ Form::text('url', null, ['class' => 'form-control input-clearable']) }}
    </div>
</div>

{{ Form::close() }}
