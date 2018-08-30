@php
    /**
     * @see \App\Application\ViewComposers\Frontend\Layout\NavigationComposer
     */

    /**
     * @var \Illuminate\Support\Collection|\App\Menus\Models\MenuItem[] $menuItems
     */
@endphp

<nav class="site-navigation">
    <!-- Title -->
    <div class="nav-title">
        <a class="title" href="/">
            {{ config('app.name') }}
        </a>

        <p class="description">
            @php /* @todo add translation */ @endphp
            A humble blog of mine.
        </p>
    </div>

    <!-- Search -->
    <fieldset class="nav-searcher">
        <legend>
            {{ __('frontend/layout/navigation.search') }}
        </legend>

        <form method="get" action="/!search">
            <div class="input-group">
                <input name="query"
                       type="search"
                       class="form-control"
                       placeholder="Search..."
                       aria-label="Search"/>

                <div class="input-group-append">
                    <button type="submit" class="btn btn-light btn-icon-only">
                        <i class="fa fa-search"></i>
                        <span class="sr-only">Search</span>
                    </button>
                </div>
            </div>
        </form>
    </fieldset>

    <!-- Menu -->
    <fieldset class="nav-menu">
        <legend>
            {{ __('frontend/layout/navigation.menu') }}
        </legend>

        <div class="nav-menu-items">
            @foreach($menuItems as $menuItem)
                <a class="nav-menu-item" href="{{ $menuItem->url }}">
                    {{ $menuItem->title }}
                </a>
            @endforeach
        </div>
    </fieldset>
</nav>