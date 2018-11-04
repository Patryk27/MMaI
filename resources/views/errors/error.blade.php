@php
    /**
     * @var int $code
     */
@endphp

@extends('errors.layout')

@section('title', __(sprintf('errors/%d.description', $code)))

@section('content')
    <h1 class="error-title">
        <i class="fa fa-exclamation-triangle"></i>

        HTTP {{ $code }}: {{ __(sprintf('errors/%d.title', $code)) }}
    </h1>

    <div class="error-description">
        {{ __(sprintf('errors/%d.description', $code)) }}
    </div>
@endsection
