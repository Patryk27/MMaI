<?php

namespace Tests\Unit\Routes;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\Page;
use App\Routes\Models\Route;

/**
 * This test checks the re-routing functionality.
 *
 * At the beginning we are setting up following routes:
 *
 *   first-route  =>  second-route  =>  [first page]
 *   third-route  =>  [second page]
 *
 * Then we re-route second route onto the third one and we should end up with:
 *
 *   first-route   => third-route
 *   second-route  => third-route
 *   third-route   => [second page]
 */
class RerouteTest extends TestCase {
    /** @var Page[] */
    private $pages;

    /** @var Route[] */
    private $routes;

    /**
     * @inheritdoc
     */
    public function setUp(): void {
        parent::setUp();

        $this->pages = [
            'first' => new Page(),
            'second' => new Page(),
        ];

        $this->pages['first']->setAttribute('id', 100);
        $this->pages['second']->setAttribute('id', 101);

        $this->routes = [
            'first' => new Route([
                'subdomain' => 'test',
                'url' => 'first-route',
            ]),

            'second' => new Route([
                'subdomain' => 'test',
                'url' => 'second-route',
            ]),

            'third' => new Route([
                'subdomain' => 'test',
                'url' => 'third-route',
            ]),
        ];

        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }

        // Set up routes' models; we cannot do it earlier (before the first
        // persisting), since we need to have been assigned all routes their
        // ids.
        $this->routes['first']->setPointsAt($this->routes['second']);
        $this->routes['second']->setPointsAt($this->pages['first']);
        $this->routes['third']->setPointsAt($this->pages['second']);

        // Re-save routes - to persist the "set points at" changes
        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testReroute(): void {
        $this->routesFacade->reroute(
            $this->routes['second'],
            $this->routes['third']
        );

        $this->assertRoutePointsAt('test', 'first-route', $this->routes['third']);
        $this->assertRoutePointsAt('test', 'second-route', $this->routes['third']);
        $this->assertRoutePointsAt('test', 'third-route', $this->pages['second']);
    }
}
