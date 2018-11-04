<div class="nav-section nav-header">
    <div class="nav-title">
        <a class="title" href="/">
            {{ config('app.name') }}
        </a>

        <p class="description">
            {{ env('APP_DESCRIPTION_' . strtoupper(Lang::locale())) }}
        </p>
    </div>
</div>
