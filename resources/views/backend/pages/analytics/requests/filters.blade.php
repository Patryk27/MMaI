{{ Form::open([
    'id' => 'requests-filters',
]) }}

<div class="row">
    {{--Url--}}
    <div class="col-xs-12 col-sm-6 col-md-3 form-group">
        {{ Form::label('url', 'URL:') }}

        {{ Form::text('url', null, [
            'class' => 'form-control input-clearable',
        ]) }}
    </div>

    {{--Performed at--}}
    <div class="col-xs-12 col-sm-6 col-md-6 form-group">
        {{ Form::label('created_from', 'Performed at:') }}

        {{ Form::datetime('created_at', null, [
            'class' => 'form-control input-clearable',
            'data-datetime' => json_encode(['mode' => 'range']),
        ]) }}
    </div>
</div>

{{ Form::close() }}
