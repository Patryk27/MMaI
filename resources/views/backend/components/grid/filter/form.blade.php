<div class="form-group">
    {{ Form::label('field', 'Field') }}
    {{ Form::select('field', [], '', ['class' => 'custom-select']) }}
</div>

<div class="form-group">
    {{ Form::label('operator', 'Operator') }}
    {{ Form::select('operator', [], '', ['class' => 'custom-select']) }}
</div>

<div class="form-group">
    {{ Form::label('value', 'Value') }}
    {{ Form::text('value', '', ['class' => 'form-control']) }}
</div>
