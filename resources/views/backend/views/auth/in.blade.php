@extends('backend.layouts.guest', [
    'view' => 'backend.auth.in',
])

@section('title', 'Signing in')

@section('content')
    {{ Form::open([
        'method' => 'post',
        'route' => 'backend.auth.do-in',
        'class' => 'auth-in-form',
    ]) }}

    <div class="form-group">
        {{ Form::label('login', 'Login') }}
        {{ Form::text('login', null, [
            'name' => 'login',
            'class' => 'form-control',
            'required' => 'true',
            'autofocus' => true,
        ]) }}
    </div>

    <div class="form-group">
        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', [
            'name' => 'password',
            'class' => 'form-control',
            'required' => true,
        ]) }}
    </div>

    <button type="submit" class="btn btn-primary">
        Let me in!
    </button>

    {{ Form::close() }}
@endsection
