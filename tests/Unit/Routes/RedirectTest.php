<?php

namespace Tests\Unit\Routes;

use App\Pages\Models\Page;
use App\Routes\Models\Route;
use Throwable;

/**
 * This test covers all the functionality related to redirecting routes.
 *
 * Assuming we have following routes already set up:
 *   A => [first page]
 *
 * When we create a redirection from B to C, we should end up with:
 *   A => B => C => D => [second page]
 */
class RedirectTest extends TestCase {

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

        $this->pages = [
            'first' => $firstPage,
            'second' => $secondPage,
        ];

        $this->routes = [
            'a' => $routeA,
            'b' => $routeB,
            'c' => $routeC,
        ];

        foreach ($this->routes as $route) {
            $this->routesRepository->persist($route);
        }
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function testFirstScenario(): void {
        // Set up the routes
        {
            $this->routes['a']->setModel($this->routes['b']);
            $this->routes['b']->setModel($this->pages['first']);

            $this->routes['c']->setModel($this->routes['d']);
            $this->routes['d']->setModel($this->pages['second']);

            foreach ($this->routes as $route) {
                $this->routesRepository->persist($route);
            }
        }

        // Execute assertions
        {
            $this->routesFacade->redirect($this->routes['b'], $this->routes['c']);

            $this->assertRoutePointsAt('test', 'a', $this->routes['b']);
            $this->assertRoutePointsAt('test', 'b', $this->routes['c']);
            $this->assertRoutePointsAt('test', 'c', $this->pages['second']);
        }
    }

}
