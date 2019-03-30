<?php

namespace App\Application\Interfaces\Http\Middleware;

use App\Application\Events\RequestServed;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DispatchRequestServedEvent {

    /** @var EventsDispatcher */
    private $eventsDispatcher;

    public function __construct(EventsDispatcher $eventsDispatcher) {
        $this->eventsDispatcher = $eventsDispatcher;
    }

    /**
     * @param Request $request
     * @param callable $next
     * @return mixed
     */
    public function handle(Request $request, callable $next) {
        return $next($request);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function terminate(Request $request, Response $response): void {
        $this->eventsDispatcher->dispatch(
            new RequestServed([
                'requestUrl' => $request->getUri(),
                'requestIp' => $request->getClientIp(),
                'responseStatusCode' => $response->getStatusCode(),
            ])
        );
    }

}
