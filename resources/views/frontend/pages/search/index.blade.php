@php
    /**
     * @var \Illuminate\Support\Collection|\App\Languages\Models\Language[] $languages
     */
@endphp

@extends('frontend.layout', [
    'pageClass' => 'frontend--pages--search--index',
])

@section('title', config('app.name'))

@section('content')
    <h1>
        Search
    </h1>

    <form id="search-form" method="post" action="#">
        {{-- Query --}}
        <div class="form-group">
            {{ Form::label('query', 'Search for:') }}

            {{ Form::input('text', 'query', '', [
                'class' => 'form-control',
                'placeholder' => 'Some keywords here',
                'autofocus' => true,
            ]) }}
        </div>
        
        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">
            Search
        </button>
    </form>
@endsection