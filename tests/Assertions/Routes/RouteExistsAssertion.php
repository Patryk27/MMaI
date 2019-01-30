<?php

namespace Tests\Assertions\Routes;

use App\Routes\Exceptions\RouteException;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Queries\GetRouteBySubdomainAndUrl;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

final class RouteExistsAssertion extends Constraint {

    /**
     * @inheritdoc
     * @param array $other
     * @return bool
     * @throws RouteException
     */
    public function matches($other): bool {
        try {
            $this->routesFacade->queryOne(
                new GetRouteBySubdomainAndUrl($other['subdomain'], $other['url'])
            );

            return true;
        } catch (RouteNotFoundException $ex) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    protected function fail($other, $description, ComparisonFailure $comparisonFailure = null): void {
        throw new ExpectationFailedException(sprintf(
            'Failed asserting that route [subdomain=%s, url=%s] exists.', $other['subdomain'], $other['url']
        ));
    }

}
