@php
    /**
     * @var string $reason
     */
@endphp

<html>
<head>
    <title>
        MMaI
    </title>

    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
<div class="container">
    <h1 class="mt-5 mb-4">
        MMaI
    </h1>

    <p>
        Hi,
    </p>

    @if ($reason === 'no-database')
        <p>
            This is a fresh installation of the MMaI CMS.
        </p>

        <p>
            You almost finished setting everything up - there's just one more thing.
        </p>

        <p>
            Run following commands in the terminal and refresh this page:
        </p>

        <pre>
            $ npm install && npm run build
            $ php artisan migrate --seed
        </pre>
    @endif
</div>
</body>
</html>