<?php

namespace Tests\Unit\Routes;

use App\Pages\Models\Page;
use App\Routes\Models\Route;

/**
 * This test checks the routes deleting functionality.
 *
 * At the beginning we are setting up following routes:
 *
 *   first-route   =>  second-route  =>  third-route  => [first page]
 *   fourth-route  =>  [second page]
 *
 * (that is: the first route points at the second one, which points at the third
 * one, while the fourth is entirely independent on them).
 *
 * Then we are deleting the second route, which should leave us with following
 * structure:
 *
 *   first-route  =>  third-route  => [first page]
 *   fourth-route
 *
 * Then we delete the first route, which should leave us with:
 *
 *   third-route  => [first page]
 *   fourth-route
 *
 * Then we delete the third route and so we end up left with only the fourth
 * one.
 */
class DeleteTest extends TestCase {

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

            'fourth' => new Route([
                'subdomain' => 'test',
                'url' => 'fourth-route',
            ]),
        ];

        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }

        // Set up routes' models; we cannot do it earlier (before the first
        // persisting), since we need to have been assigned all routes their
        // ids.
        $this->routes['first']->setPointsAt($this->routes['second']);
        $this->routes['second']->setPointsAt($this->routes['third']);
        $this->routes['third']->setPointsAt($this->pages['first']);
        $this->routes['fourth']->setPointsAt($this->pages['second']);

        // Re-save routes - to persist the "set points at" changes
        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }
    }

    /**
     * @return void
     */
    public function testDelete(): void {
        $this->routesFacade->delete(
            $this->routes['second']
        );

        $this->assertRouteExists('test', 'first-route');
        $this->assertRouteDoesNotExist('test', 'second-route');
        $this->assertRouteExists('test', 'third-route');
        $this->assertRouteExists('test', 'fourth-route');

        $this->assertRoutePointsAt('test', 'first-route', $this->routes['third']);
        $this->assertRoutePointsAt('test', 'third-route', $this->pages['first']);
        $this->assertRoutePointsAt('test', 'fourth-route', $this->pages['second']);

        // ----- //

        $this->routesFacade->delete(
            $this->routes['first']
        );

        $this->assertRouteDoesNotExist('test', 'first-route');
        $this->assertRouteExists('test', 'third-route');
        $this->assertRouteExists('test', 'fourth-route');

        $this->assertRoutePointsAt('test', 'third-route', $this->pages['first']);
        $this->assertRoutePointsAt('test', 'fourth-route', $this->pages['second']);

        // ----- //

        $this->routesFacade->delete(
            $this->routes['third']
        );

        $this->assertRouteDoesNotExist('test', 'third-route');
        $this->assertRouteExists('test', 'fourth-route');

        $this->assertRoutePointsAt('test', 'fourth-route', $this->pages['second']);
    }

}
