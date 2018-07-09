@php
    /**
     * @see \App\ViewComposers\Backend\Layout\NavigationComposer
     */

    /**
     * @var array $menuItems
     */
@endphp

<nav class="site-navigation">
    <div class="nav-title">
        MMaI
    </div>

    <div class="nav-items">
        @foreach($menuItems as $menuItem)
            <a href="{{ $menuItem['url'] }}" class="nav-item {{ $menuItem['active'] ? 'active' : '' }}">
                {{ $menuItem['title'] }}
            </a>
        @endforeach
    </div>
</nav>