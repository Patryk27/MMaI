@php
    /**
     * @var \Illuminate\Support\Collection|\App\Menus\Models\MenuItem[] $menuItems
     */
@endphp

<div class="nav-section nav-menu">
    <div class="nav-section-name">
        {{ __('frontend/layout/navigation.menu') }}
    </div>

    <div class="nav-menu-items">
        @foreach($menuItems as $menuItem)
            <a class="nav-menu-item" href="{{ $menuItem->url }}">
                {{ $menuItem->title }}
            </a>
        @endforeach
    </div>
</div>
