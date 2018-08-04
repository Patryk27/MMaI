@extends('backend.layouts.authenticated', [
    'pageClass' => 'backend--pages--pages--create',
])

@section('content')
    <div class="title-wrapper">
        <h1 class="title">
            Creating page
        </h1>
    </div>

    <div id="form"
         data-method="post"
         data-url="{{ route('backend.pages.store') }}">
        @include('backend.pages.pages.create-edit.form', [
            'page' => new \App\Pages\Models\Page(),
        ])
    </div>
@endsection