@php
    /**
     * @see \App\Application\ViewComposers\Frontend\Layout\NavigationComposer
     */
@endphp

<nav class="site-navigation">
    @include('frontend.layout.navigation.header')
    @include('frontend.layout.navigation.search')
    @include('frontend.layout.navigation.newest-posts')
    @include('frontend.layout.navigation.menu')
</nav>
