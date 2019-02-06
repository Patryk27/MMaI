@php
    /**
     * @var \Illuminate\Support\Collection|\App\Menus\Models\MenuItem[] $menuItems
     */
@endphp

<div id="nav-menu" class="nav-section">
    <div class="nav-menu-items">
        @foreach($menuItems as $menuItem)
            <a class="nav-menu-item" href="{{ $menuItem->url }}">
                {{ $menuItem->title }}
            </a>
        @endforeach
    </div>
</div>
