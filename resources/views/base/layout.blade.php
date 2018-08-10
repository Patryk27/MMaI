@php
    /**
     * @var string $layoutClass
     * @var string $pageClass
     */
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>
        @yield('layout-title')
    </title>

    <link rel="stylesheet" type="text/css" href="/css/app.css"/>
</head>

<body class="{{ $layoutClass . ' ' . $pageClass }}">
@yield('layout-content')
</body>

<script type="application/javascript" src="/js/app.js"></script>
</html>