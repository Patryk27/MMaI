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
                <div class="icon">
                    <i class="{{ $menuItem['icon'] }}"></i>
                </div>

                <div class="title">
                    {{ $menuItem['title'] }}
                </div>
            </a>
        @endforeach
    </div>
</nav>