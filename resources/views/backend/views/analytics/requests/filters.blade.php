{{ Form::open([
    'id' => 'requests-filters',
]) }}

<div class="row">
    {{-- Url --}}
    <div class="col-xs-12 col-sm-12 col-md-3 form-group">
        {{ Form::label('request_url', 'URL:') }}

        {{ Form::text('request_url', null, [
            'class' => 'form-control input-clearable',
        ]) }}
    </div>

    {{-- Status code --}}
    <div class="col-xs-12 col-sm-12 col-md-3 form-group">
        {{ Form::label('response_status_code', 'Status code:') }}

        {{ Form::text('response_status_code', null, [
            'class' => 'form-control input-clearable',
        ]) }}
    </div>

    {{-- Performed at --}}
    <div class="col-xs-12 col-sm-12 col-md-6 form-group">
        {{ Form::label('created_at', 'Performed at:') }}

        {{ Form::datetime('created_at', null, [
            'class' => 'form-control input-clearable',
            'data-datetime' => json_encode(['mode' => 'range']),
        ]) }}
    </div>
</div>

{{ Form::close() }}
