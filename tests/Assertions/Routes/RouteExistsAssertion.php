<?php

namespace Tests\Assertions\Routes;

use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Queries\GetRouteByUrlQuery;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

class RouteExistsAssertion extends Constraint
{

    /**
     * @inheritdoc
     *
     * @param string $other
     */
    public function matches($other): bool
    {
        try {
            $this->routesFacade->queryOne(
                new GetRouteByUrlQuery($other)
            );

            return true;
        } catch (RouteNotFoundException $ex) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    protected function fail($other, $description, ComparisonFailure $comparisonFailure = null): void
    {
        throw new ExpectationFailedException(
            sprintf('Failed asserting that route with url [%s] exists.', $other)
        );
    }

}
