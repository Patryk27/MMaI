@php
    /**
     * @see \App\Application\ViewComposers\Frontend\Layout\NavigationComposer
     */
@endphp

<nav class="site-navigation">
    @include('frontend.components.layout.navigation.header')
    @include('frontend.components.layout.navigation.menu')
    @include('frontend.components.layout.navigation.search')
</nav>
