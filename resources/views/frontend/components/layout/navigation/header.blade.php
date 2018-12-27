@php
    /**
     * @var App\Websites\Models\Website $website
     */
@endphp

<div class="nav-section nav-header">
    <div class="nav-title">
        <a class="title" href="/">
            {{ config('app.name') }}
        </a>

        <p class="description">
            {{ $website->description }}
        </p>
    </div>
</div>
