<?php

namespace Tests\Unit\Routes;

use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;

/**
 * This test checks the routes deleting functionality.
 *
 * At the beginning we are setting up following routes:
 *
 *   first-route   =>  second-route  =>  third-route  => [first page variant]
 *   fourth-route  =>  [second page variant]
 *
 * (that is: the first route points at the second one, which points at the third
 * one, while the fourth is entirely independent on them).
 *
 * Then we are deleting the second route, which should leave us with following
 * structure:
 *
 *   first-route  =>  third-route  => [first page variant]
 *   fourth-route
 *
 * Then we delete the first route, which should leave us with:
 *
 *   third-route  => [first page variant]
 *   fourth-route
 *
 * Then we delete the third route and so we end up left with only the fourth
 * one.
 */
class DeleteTest extends TestCase
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
                'url' => 'first-route',
            ]),

            'second' => new Route([
                'url' => 'second-route',
            ]),

            'third' => new Route([
                'url' => 'third-route',
            ]),

            'fourth' => new Route([
                'url' => 'fourth-route',
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
        $this->routes['second']->setPointsAt($this->routes['third']);
        $this->routes['third']->setPointsAt($this->pageVariants['first']);
        $this->routes['fourth']->setPointsAt($this->pageVariants['second']);

        // Re-save routes - to save the "set points at" changes
        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        $this->routesFacade->delete(
            $this->routes['second']
        );

        $this->assertRouteExists('first-route');
        $this->assertRouteDoesNotExist('second-route');
        $this->assertRouteExists('third-route');
        $this->assertRouteExists('fourth-route');

        $this->assertRoutePointsAt('first-route', $this->routes['third']);
        $this->assertRoutePointsAt('third-route', $this->pageVariants['first']);
        $this->assertRoutePointsAt('fourth-route', $this->pageVariants['second']);

        // ----- //

        $this->routesFacade->delete(
            $this->routes['first']
        );

        $this->assertRouteDoesNotExist('first-route');
        $this->assertRouteExists('third-route');
        $this->assertRouteExists('fourth-route');

        $this->assertRoutePointsAt('third-route', $this->pageVariants['first']);
        $this->assertRoutePointsAt('fourth-route', $this->pageVariants['second']);

        // ----- //

        $this->routesFacade->delete(
            $this->routes['third']
        );

        $this->assertRouteDoesNotExist('third-route');
        $this->assertRouteExists('fourth-route');

        $this->assertRoutePointsAt('fourth-route', $this->pageVariants['second']);
    }

}