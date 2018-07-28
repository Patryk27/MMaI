<?php

namespace Tests\Unit\Routes;

use App\Core\Exceptions\Exception;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteByUrlQuery;

class QueryTest extends TestCase
{

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->routesRepository->persist(
            new Route([
                'url' => '/first-route',

                'model_type' => 'some-model',
                'model_id' => 1,
            ])
        );

        $this->routesRepository->persist(
            new Route([
                'url' => '/second-route',

                'model_type' => 'some-model',
                'model_id' => 2,
            ])
        );
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testGetRouteByUrlQueryFindsTheFirstRoute(): void
    {
        $routeA = $this->routesFacade->queryOne(
            new GetRouteByUrlQuery('/first-route')
        );

        $this->assertEquals('some-model', $routeA->model_type);
        $this->assertEquals(1, $routeA->model_id);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testGetRouteByUrlQueryFailsOnMadeUpRoute(): void
    {
        $this->expectException(RouteNotFoundException::class);

        $this->routesFacade->queryOne(
            new GetRouteByUrlQuery('some not existing route')
        );
    }

}