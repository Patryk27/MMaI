<?php

namespace App\Application\Http\Middleware;

use App\Analytics\AnalyticsFacade;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogRequest
{

    /**
     * @var AnalyticsFacade
     */
    private $analyticsFacade;

    /**
     * @param AnalyticsFacade $analyticsFacade
     */
    public function __construct(
        AnalyticsFacade $analyticsFacade
    ) {
        $this->analyticsFacade = $analyticsFacade;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        if ($response->getStatusCode() === Response::HTTP_OK) {
            $this->analyticsFacade->logRequestServed($request);
        } else {
            $this->analyticsFacade->logRequestFailed($request, $response);
        }
    }

}
