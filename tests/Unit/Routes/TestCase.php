<?php

namespace Tests\Unit\Routes;

use App\Core\Models\Interfaces\Morphable;
use App\Core\Repositories\InMemoryRepository;
use App\Routes\Implementation\Repositories\InMemoryRoutesRepository;
use App\Routes\RoutesFacade;
use App\Routes\RoutesFactory;
use Tests\Assertions\Routes\RouteDoesNotExistAssertion;
use Tests\Assertions\Routes\RouteExistsAssertion;
use Tests\Assertions\Routes\RoutePointsAtAssertion;
use Tests\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    /**
     * @var InMemoryRoutesRepository
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
        parent::setUp();

        $this->routesRepository = new InMemoryRoutesRepository(
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