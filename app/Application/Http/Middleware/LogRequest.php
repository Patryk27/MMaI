<?php

namespace App\Application\Http\Middleware;

use App\Application\Events\RequestServed;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class LogRequest
{
    /** @var EventsDispatcher */
    private $eventsDispatcher;

    public function __construct(EventsDispatcher $eventsDispatcher)
    {
        $this->eventsDispatcher = $eventsDispatcher;
    }

    /**
     * @param Request $request
     * @param callable $next
     * @return mixed
     */
    public function handle(Request $request, callable $next)
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
        $this->eventsDispatcher->dispatch(
            new RequestServed([
                'requestUrl' => $request->getUri(),
                'requestIp' => $request->getClientIp(),
                'responseStatusCode' => $response->getStatusCode(),
            ])
        );
    }
}
