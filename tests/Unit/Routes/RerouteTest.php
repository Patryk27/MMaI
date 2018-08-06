<?php

namespace Tests\Unit\Routes;

use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;

/**
 * This test checks the re-routing functionality.
 *
 * At the beginning we are setting up following routes:
 *
 *   /first-route  =>  /second-route  =>  [first page variant]
 *   /third-route  =>  [second page variant]
 *
 * Then we re-route second route onto the third one and we should end up with:
 *
 *   /first-route   => /third-route
 *   /second-route  => /third-route
 *   /third-route   => [second page variant]
 */
class RerouteTest extends TestCase
{

    /**
     * @var PageVariant[]
     */
    private $pageVariants;

    /**
     * @var Route[]
     */
    private $routes;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        // Set up users
        $this->pageVariants = [
            'first' => new PageVariant(),
            'second' => new PageVariant(),
        ];

        $this->pageVariants['first']->setAttribute('id', 100);
        $this->pageVariants['second']->setAttribute('id', 101);

        // Set up routes
        $this->routes = [
            'first' => new Route([
                'url' => '/first-route',
            ]),

            'second' => new Route([
                'url' => '/second-route',
            ]),

            'third' => new Route([
                'url' => '/third-route',
            ]),
        ];

        // Save routes to the database
        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }

        // Set up routes' models; we cannot do it earlier (before the first
        // persisting), since we need to have been assigned all routes their
        // ids.
        $this->routes['first']->setPointsAt($this->routes['second']);
        $this->routes['second']->setPointsAt($this->pageVariants['first']);
        $this->routes['third']->setPointsAt($this->pageVariants['second']);

        // Re-save routes - to save the "set points at" changes
        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }
    }

    /**
     * @return void
     */
    public function testReroute(): void
    {
        $this->routesFacade->reroute(
            $this->routes['second'],
            $this->routes['third']
        );

        $this->assertRoutePointsAt('/first-route', $this->routes['third']);
        $this->assertRoutePointsAt('/second-route', $this->routes['third']);
        $this->assertRoutePointsAt('/third-route', $this->pageVariants['second']);
    }

}