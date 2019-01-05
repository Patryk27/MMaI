<?php

namespace Tests\Unit\Routes;

use App\Core\Models\Morphable;
use App\Core\Repositories\InMemoryRepository;
use App\Routes\Implementation\Repositories\InMemoryRoutesRepository;
use App\Routes\RoutesFacade;
use App\Routes\RoutesFactory;
use Tests\Assertions\Routes\RouteDoesNotExistAssertion;
use Tests\Assertions\Routes\RouteExistsAssertion;
use Tests\Assertions\Routes\RoutePointsAtAssertion;
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
        $payload = [
            'subdomain' => $subdomain,
            'url' => $url,
        ];

        $this->assertThat($payload, new RouteExistsAssertion($this->routesFacade));
    }

    /**
     * @param string $subdomain
     * @param string $url
     * @return void
     */
    protected function assertRouteDoesNotExist(string $subdomain, string $url): void {
        $payload = [
            'subdomain' => $subdomain,
            'url' => $url,
        ];

        $this->assertThat($payload, new RouteDoesNotExistAssertion($this->routesFacade));
    }

    /**
     * @param string $subdomain
     * @param string $url
     * @param Morphable $morphable
     * @return void
     */
    protected function assertRoutePointsAt(string $subdomain, string $url, Morphable $morphable): void {
        $payload = [
            'subdomain' => $subdomain,
            'url' => $url,
            'morphable' => $morphable,
        ];

        $this->assertThat($payload, new RoutePointsAtAssertion($this->routesFacade));
    }
}
