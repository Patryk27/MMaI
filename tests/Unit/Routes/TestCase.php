<?php

namespace Tests\Unit\Routes;

use App\Core\Models\Interfaces\Morphable;
use App\Core\Repositories\InMemoryRepository;
use App\Routes\Internal\Repositories\RoutesInMemoryRepository;
use App\Routes\RoutesFacade;
use App\Routes\RoutesFactory;
use Tests\Assertions\Routes\RouteDoesNotExistAssertion;
use Tests\Assertions\Routes\RouteExistsAssertion;
use Tests\Assertions\Routes\RoutePointsAtAssertion;
use Tests\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    /**
     * @var RoutesInMemoryRepository
     */
    protected $routesRepository;

    /**
     * @var RoutesFacade
     */
    protected $routesFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->routesRepository = new RoutesInMemoryRepository(
            new InMemoryRepository()
        );

        $this->routesFacade = RoutesFactory::build($this->routesRepository);
    }

    /**
     * @param string $url
     * @return void
     */
    protected function assertRouteExists(string $url): void
    {
        $this->assertThat($url, new RouteExistsAssertion($this->routesFacade));
    }

    /**
     * @param string $url
     * @return void
     */
    protected function assertRouteDoesNotExist(string $url): void
    {
        $this->assertThat($url, new RouteDoesNotExistAssertion($this->routesFacade));
    }

    /**
     * @param string $url
     * @param Morphable $morphable
     * @return void
     */
    protected function assertRoutePointsAt(string $url, Morphable $morphable): void
    {
        $payload = [
            'url' => $url,
            'morphable' => $morphable,
        ];

        $this->assertThat($payload, new RoutePointsAtAssertion($this->routesFacade));
    }

}