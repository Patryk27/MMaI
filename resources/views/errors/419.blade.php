{{-- @todo translation --}}

@extends('errors.layout')

@section('title', 'Authorization error')

@section('content')
    <h1 class="error-code">
        Authorization error
    </h1>

    <div class="error-description">
        Sorry, there has been an error trying to authorize you - please try again in a minute.
    </div>
@endsection
