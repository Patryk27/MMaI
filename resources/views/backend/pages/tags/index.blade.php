@php
    /**
     * @var \Illuminate\Support\Collection|int[] $languages
     */
@endphp

@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--tags--index',
])

@section('content')
    @include('backend.pages.tags.index.create-tag-modal')
    @include('backend.pages.tags.index.header')
    @include('backend.pages.tags.index.table')
@endsection