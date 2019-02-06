@php
    /**
     * @var App\Websites\Models\Website $website
     */
@endphp

<div id="nav-header" class="nav-section">
    <div {{-- wrapper required for Flexbox --}}>
        <a class="title" href="/">
            {{ config('app.name') }}
        </a>

        <p class="description">
            {{ $website->description }}
        </p>
    </div>
</div>
