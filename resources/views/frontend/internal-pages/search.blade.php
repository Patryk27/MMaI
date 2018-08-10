@php
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\App\Languages\Models\Language[] $languages
     */
@endphp

@extends('frontend.layout', [
    'pageClass' => 'frontend--internal-pages--home',
])

@section('title', config('app.name'))

@section('content')
    <h1>
        Search
    </h1>

    <form id="search-form">
        {{-- Query --}}
        <div class="form-group">
            {{ Form::label('query', 'Search for:') }}

            {{ Form::input('text', 'query', '', [
                'class' => 'form-control',
                'placeholder' => 'Enter some keywords here.',
                'autofocus' => true,
            ]) }}
        </div>

        {{-- Languages --}}
        <div class="form-group">
            {{ Form::label('language_ids[]', 'Take into account following languages:') }}

            {{ Form::select('language_ids[]', $languages->sortBy('native_name')->pluck('native_name', 'id'), null, [
                'class' => 'form-control',
                'multiple' => true,
            ]) }}
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">
            Search
        </button>
    </form>
@endsection