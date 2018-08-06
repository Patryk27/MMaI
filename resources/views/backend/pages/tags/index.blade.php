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

    <div id="tags-loader" data-loader-type="tile">
        {{ Form::open([
            'id' => 'tags-form',
            'class' => 'form-inline',
        ]) }}

        {{ Form::label('language_id', 'Show tags for language:') }}

        {{ Form::select('language_id', $languages, null, [
            'class' => 'form-control',
        ]) }}

        {{ Form::close() }}

        <table id="tags-table" class="table table-striped table-dark">
            <thead>
            <tr>
                <th data-datatable-column='{"name": "id", "orderable": true}'>
                    Id
                </th>

                <th data-datatable-column='{"name": "name", "orderable": true}'>
                    Name
                </th>

                <th data-datatable-column='{"name": "page_variant_count", "orderable": true}'>
                    Number of pages
                </th>

                <th data-datatable-column='{"name": "actions"}'>
                    &nbsp;
                </th>
            </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>
@endsection