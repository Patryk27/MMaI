<?php

namespace Tests\Assertions\Routes;

use App\Routes\Exceptions\RouteException;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Queries\GetRouteBySubdomainAndUrlQuery;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

class RouteDoesNotExistAssertion extends Constraint
{

    /**
     * @inheritdoc
     *
     * @param array $other
     * @return bool
     *
     * @throws RouteException
     */
    public function matches($other): bool
    {
        try {
            $this->routesFacade->queryOne(
                new GetRouteBySubdomainAndUrlQuery($other['subdomain'], $other['url'])
            );

            return false;
        } catch (RouteNotFoundException $ex) {
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    protected function fail($other, $description, ComparisonFailure $comparisonFailure = null): void
    {
        throw new ExpectationFailedException(
            sprintf('Failed asserting that route [subdomain=%s, url=%s] does not exist.', $other['subdomain'], $other['url'])
        );
    }

}
