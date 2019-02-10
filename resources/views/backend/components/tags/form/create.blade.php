<div class="form-group">
    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', '', ['class' => 'form-control']) }}
</div>

<div class="form-group">
    {{ Form::label('website_id', 'Website') }}
    {{ Form::select('website_id', $websites, null, ['class' => 'custom-select']) }}
</div>
