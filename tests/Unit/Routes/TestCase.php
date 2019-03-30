<?php

namespace Tests\Unit\Routes;

use App\Core\Models\Morphable;
use App\Core\Repositories\InMemoryRepository;
use App\Routes\Implementation\Repositories\InMemoryRoutesRepository;
use App\Routes\RoutesFacade;
use App\Routes\RoutesFactory;
use Tests\Constraints\Routes\RouteDoesNotExist;
use Tests\Constraints\Routes\RouteExists;
use Tests\Constraints\Routes\RoutePointsAt;
use Tests\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

    /** @var InMemoryRoutesRepository */
    protected $routesRepository;

    /** @var RoutesFacade */
    protected $routesFacade;

    /**
     * @return void
     */
    public function setUp(): void {
        parent::setUp();

        $this->routesRepository = new InMemoryRoutesRepository(
            new InMemoryRepository()
        );

        $this->routesFacade = RoutesFactory::build($this->routesRepository);
    }

    /**
     * @param string $subdomain
     * @param string $url
     * @return void
     */
    protected function assertRouteExists(string $subdomain, string $url): void {
        $route = [
            'subdomain' => $subdomain,
            'url' => $url,
        ];

        $this->assertThat($route, new RouteExists($this->routesFacade));
    }

    /**
     * @param string $subdomain
     * @param string $url
     * @return void
     */
    protected function assertRouteDoesNotExist(string $subdomain, string $url): void {
        $route = [
            'subdomain' => $subdomain,
            'url' => $url,
        ];

        $this->assertThat($route, new RouteDoesNotExist($this->routesFacade));
    }

    /**
     * @param string $subdomain
     * @param string $url
     * @param Morphable $model
     * @return void
     */
    protected function assertRoutePointsAt(string $subdomain, string $url, Morphable $model): void {
        $route = [
            'subdomain' => $subdomain,
            'url' => $url,
            'model' => $model,
        ];

        $this->assertThat($route, new RoutePointsAt($this->routesFacade));
    }

}
