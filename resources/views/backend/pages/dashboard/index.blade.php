@php
    /**
     * @var \App\Users\Models\User $user
     */
@endphp

@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--dashboard--index',
])

@section('title', 'Dashboard')

@section('content')
    <div class="content-header">
        <h1 class="title">
            Dashboard
        </h1>
    </div>

    <div>
        Welcome, {{ $user->name }}!
    </div>
@endsection
