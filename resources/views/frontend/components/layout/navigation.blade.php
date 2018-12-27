@php
    /**
     * @see \App\Application\ViewComposers\Frontend\Layout\NavigationComposer
     */
@endphp

<nav class="site-navigation">
    @include('frontend.components.layout.navigation.header')
    @include('frontend.components.layout.navigation.search')
    @include('frontend.components.layout.navigation.newest-posts')
    @include('frontend.components.layout.navigation.menu')
</nav>
