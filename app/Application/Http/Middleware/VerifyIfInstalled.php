<?php

namespace App\Application\Http\Middleware;

use App\Routes\RoutesFacade;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class VerifyIfInstalled
{
    /** @var DatabaseConnection */
    private $db;

    /** @var RoutesFacade */
    private $routesFacade;

    public function __construct(
        DatabaseConnection $db,
        RoutesFacade $routesFacade
    ) {
        $this->db = $db;
        $this->routesFacade = $routesFacade;
    }

    /**
     * @param Request $request
     * @param callable $next
     * @return mixed
     */
    public function handle(Request $request, callable $next)
    {
        $schemaBuilder = $this->db->getSchemaBuilder();

        if (!$schemaBuilder->hasTable('migrations')) {
            return $this->buildResponse([
                'reason' => 'no-database',
            ]);
        }

        return $next($request);
    }

    /**
     * @param array $viewData
     * @return Response
     */
    private function buildResponse(array $viewData): Response
    {
        return new Response(
            view('frontend.views.application-not-installed', $viewData)
        );
    }
}
