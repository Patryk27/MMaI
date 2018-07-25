<?php

namespace Tests\Unit\Routes;

use App\Core\Repositories\InMemoryRepository;
use App\Routes\Internal\Repositories\RoutesInMemoryRepository;
use App\Routes\Internal\Services\RoutesDeleter;
use App\Routes\Internal\Services\RoutesDispatcher;
use App\Routes\Internal\Services\RoutesQuerier;
use App\Routes\Internal\Services\RoutesRerouter;
use App\Routes\RoutesFacade;
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

        $routesDeleter = new RoutesDeleter();
        $routesDispatcher = new RoutesDispatcher();
        $routesRerouter = new RoutesRerouter();
        $routesQuerier = new RoutesQuerier($this->routesRepository);

        $this->routesFacade = new RoutesFacade(
            $routesDeleter,
            $routesDispatcher,
            $routesRerouter,
            $routesQuerier
        );
    }

}