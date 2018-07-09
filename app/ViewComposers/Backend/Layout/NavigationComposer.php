<?php

namespace App\ViewComposers\Backend\Layout;

use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Route;

class NavigationComposer
{

    private const ITEMS = [
        'backend.dashboard' => 'Dashboard',
        'backend.pages' => 'Pages',
        'backend.posts' => 'Posts',
        'backend.tags' => 'Tags',
//        'backend.routes' => 'Routes',
//        'backend.languages' => 'Languages',
//        'backend.users' => 'Users',
        'backend.about' => 'About',
    ];

    /**
     * @var UrlGeneratorContract
     */
    private $urlGenerator;

    /**
     * @var Route
     */
    private $route;

    /**
     * @param UrlGeneratorContract $urlGenerator
     * @param Route|null $route
     */
    public function __construct(
        UrlGeneratorContract $urlGenerator,
        ?Route $route = null // Default `null` value has been provided for the CLI environment (where no route can be passed)
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->route = $route;
    }

    /**
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with([
            'menuItems' => $this->getMenuItems(),
        ]);
    }

    /**
     * @return array
     */
    private function getMenuItems(): array
    {
        $items = [];

        foreach (self::ITEMS as $route => $title) {
            $items[] = [
                'url' => $this->urlGenerator->route($route . '.index'),
                'title' => $title,
                'active' => $this->isRouteActive($route),
            ];
        }

        return $items;
    }

    /**
     * Returns `true` if given route is active.
     *
     * For a route to be active, user must either be viewing right now that
     * precise route or one of its descendants (e.g. "foo.bar" or
     * "foo.bar.baz").
     *
     * @param string $route
     * @return bool
     */
    private function isRouteActive(string $route): bool
    {
        return starts_with($this->route->getName(), $route);
    }

}