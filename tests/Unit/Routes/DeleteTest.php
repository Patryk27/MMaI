<?php

namespace Tests\Unit\Routes;

use App\Pages\Models\Page;
use App\Routes\Models\Route;

/**
 * This test covers all the functionality related to deleting routes.
 *
 * Basically - we're starting with following routes:
 *   A => B => C => [first page]
 *   D => [second page]
 *
 * (that is: route A redirects to route B, which redirects to route C, which
 * eventually redirects to the proper page)
 *
 * We're then deleting the B route, which should leave us with following state:
 *   A => C => [first page]
 *   D => [second page]
 *
 * Then we're deleting the A route, which should leave us with:
 *   C => [first page]
 *   D => [second page]
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

        $firstPage = new Page();
        $firstPage->setAttribute('id', 1);

        $secondPage = new Page();
        $secondPage->setAttribute('id', 2);

        $routeA = new Route([
            'subdomain' => 'test',
            'url' => 'a',
        ]);

        $routeB = new Route([
            'subdomain' => 'test',
            'url' => 'b',
        ]);

        $routeC = new Route([
            'subdomain' => 'test',
            'url' => 'c',
        ]);

        $routeD = new Route([
            'subdomain' => 'test',
            'url' => 'd',
        ]);

        $this->pages = [
            'first' => $firstPage,
            'second' => $secondPage,
        ];

        $this->routes = [
            'a' => $routeA,
            'b' => $routeB,
            'c' => $routeC,
            'd' => $routeD,
        ];

        // We need to save all the routes before setting the relationships,
        // because we need to have their ids
        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }

        $routeA->setModel($routeB);
        $routeB->setModel($routeC);
        $routeC->setModel($firstPage);
        $routeD->setModel($secondPage);

        // Now we have to save them for the second time, since otherwise the
        // just-modified versions would not be persisted
        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }
    }

    /**
     * @return void
     */
    public function testDelete(): void {
        // Let's delete the "B" route and see what happens
        {
            $this->routesFacade->delete($this->routes['b']);

            $this->assertRouteExists('test', 'a');
            $this->assertRouteDoesNotExist('test', 'b');
            $this->assertRouteExists('test', 'c');
            $this->assertRouteExists('test', 'd');

            $this->assertRoutePointsAt('test', 'a', $this->routes['c']);
            $this->assertRoutePointsAt('test', 'c', $this->pages['first']);
            $this->assertRoutePointsAt('test', 'd', $this->pages['second']);
        }

        // Let's delete the "A" route and see what happens
        {
            $this->routesFacade->delete($this->routes['a']);

            $this->assertRouteDoesNotExist('test', 'a');
            $this->assertRouteDoesNotExist('test', 'b');
            $this->assertRouteExists('test', 'c');
            $this->assertRouteExists('test', 'd');

            $this->assertRoutePointsAt('test', 'c', $this->pages['first']);
            $this->assertRoutePointsAt('test', 'd', $this->pages['second']);
        }

        // Let's delete the "C" route and see what happens
        {
            $this->routesFacade->delete($this->routes['c']);

            $this->assertRouteDoesNotExist('test', 'a');
            $this->assertRouteDoesNotExist('test', 'b');
            $this->assertRouteDoesNotExist('test', 'c');
            $this->assertRouteExists('test', 'd');

            $this->assertRoutePointsAt('test', 'd', $this->pages['second']);
        }
    }

}
