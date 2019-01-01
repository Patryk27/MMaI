@php
    /**
     * @var string $layout
     * @var string $view
     */
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    @yield('layout-meta')

    <title>
        @yield('layout-title')
    </title>

    <link rel="stylesheet" type="text/css" href="/assets/app.css"/>
</head>

<body data-layout="{{ $layout }}" data-view="{{ $view }}">
    <div id="overlay">
        <div class="loader loader-tile"></div>
    </div>

    @yield('layout-content')
</body>

<script type="application/javascript" src="/assets/app.js"></script>
</html>
