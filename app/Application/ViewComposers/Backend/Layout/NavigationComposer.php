<?php

namespace App\Application\ViewComposers\Backend\Layout;

use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Route;

class NavigationComposer
{
    private const ITEMS = [
        'backend.dashboard' => [
            'icon' => 'fa fa-tachometer-alt',
            'title' => 'Dashboard',
        ],

        'backend.analytics' => [
            'icon' => 'fa fa-chart-line',
            'title' => 'Analytics',
        ],

        'backend.pages' => [
            'icon' => 'fa fa-file',
            'title' => 'Pages & Posts',
        ],

        'backend.tags' => [
            'icon' => 'fa fa-tag',
            'title' => 'Tags',
        ],
    ];

    /** @var UrlGeneratorContract */
    private $urlGenerator;

    /** @var Route */
    private $route;

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

        foreach (self::ITEMS as $route => $item) {
            $items[] = [
                'url' => $this->urlGenerator->route($route . '.index'),
                'title' => $item['title'],
                'icon' => $item['icon'],
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
