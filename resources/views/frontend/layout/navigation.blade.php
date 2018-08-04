@php
    /**
     * @see \App\App\ViewComposers\Frontend\Layout\NavigationComposer
     */

    /**
     * @var string $homeUrl
     * @var \Illuminate\Support\Collection|\App\Menus\Models\MenuItem[] $menuItems
     */
@endphp

<nav class="site-navigation">
    <!-- Title -->
    <div class="nav-title">
        <a class="title" href="{{ $homeUrl }}">
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

        <form>
            <div class="form-row">
                <div class="col">
                    <input class="form-control mr-sm-2"
                           type="search"
                           placeholder="Search..."
                           aria-label="Search"/>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-light my-2 my-sm-0">
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
                <a class="nav-menu-item" href="{{ $menuItem->getTargetUrl() }}">
                    {{ $menuItem->title }}
                </a>
            @endforeach
        </div>
    </fieldset>
</nav>