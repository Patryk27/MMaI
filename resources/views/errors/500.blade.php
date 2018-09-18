{{-- @todo translation --}}

@extends('errors.layout')

@section('title', 'Application error')

@section('content')
    <h1 class="error-code">
        Application error
    </h1>

    <div class="error-description">
        Whoopsie - something went wrong and application crashed; please try again in a minute.
    </div>
@endsection
