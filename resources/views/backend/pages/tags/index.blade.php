@php
    /**
     * @var \Illuminate\Support\Collection|int[] $languages
     */
@endphp

@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--tags--index',
])

@section('content')
    <div class="title-wrapper">
        <h1 class="title">
            Tags
        </h1>
    </div>

    <div id="tags-form" class="form-inline">
        {!! Form::label('language_id', 'Show tags for language:') !!}

        {!! Form::select('language_id', $languages, null, [
            'class' => 'form-control',
        ]) !!}
    </div>

    <div id="tags-loader" class="loader loader-tile"></div>

    <table id="tags-table" class="table table-striped table-dark">
        <thead>
        <tr>
            <th>Id</th>
            <th>Tag</th>
            <th>Number of posts</th>
            <th>&nbsp;</th>
        </tr>
        </thead>

        <tbody>

        </tbody>
    </table>
@endsection