<?php

namespace App\Application\Http\Middleware;

use App\Routes\Exceptions\RouteException;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Queries\GetRouteByUrlQuery;
use App\Routes\RoutesFacade;
use Closure;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyIsInstalled
{

    /**
     * @var DatabaseConnection
     */
    private $db;

    /**
     * @var RoutesFacade
     */
    private $routesFacade;

    /**
     * @param DatabaseConnection $db
     * @param RoutesFacade $routesFacade
     */
    public function __construct(
        DatabaseConnection $db,
        RoutesFacade $routesFacade
    ) {
        $this->db = $db;
        $this->routesFacade = $routesFacade;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     *
     * @throws RouteException
     */
    public function handle($request, Closure $next)
    {
        // Validate that the database contains "migrations" table
        $schemaBuilder = $this->db->getSchemaBuilder();

        if (!$schemaBuilder->hasTable('migrations')) {
            return $this->buildResponse([
                'reason' => 'no-database',
            ]);
        }

        // Validate that there exists the default route
        try {
            $this->routesFacade->queryOne(
                new GetRouteByUrlQuery('/')
            );
        } catch (RouteNotFoundException $ex) {
            return $this->buildResponse([
                'reason' => 'no-default-route',
            ]);
        }

        // If all conditions are met, proceed
        return $next($request);
    }

    /**
     * @param array $viewData
     * @return Response
     */
    private function buildResponse(array $viewData): Response
    {
        return new Response(
            view('frontend.intrinsic-pages.not-installed', $viewData)
        );
    }

}