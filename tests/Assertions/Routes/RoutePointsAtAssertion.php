<?php

namespace Tests\Assertions\Routes;

use App\Core\Models\Morphable;
use App\Routes\Exceptions\RouteException;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Queries\GetRouteBySubdomainAndUrlQuery;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

class RoutePointsAtAssertion extends Constraint
{

    /**
     * @inheritdoc
     *
     * @param Morphable $other
     * @return bool
     *
     * @throws RouteException
     */
    public function matches($other): bool
    {
        try {
            /**
             * @var string $subdomain
             */
            $subdomain = $other['subdomain'];

            /**
             * @var string $url
             */
            $url = $other['url'];

            /**
             * @var Morphable $morphable
             */
            $morphable = $other['morphable'];

            $route = $this->routesFacade->queryOne(
                new GetRouteBySubdomainAndUrlQuery($subdomain, $url)
            );

            return $route->model_id === $morphable->getMorphableId()
                && $route->model_type === $morphable::getMorphableType();
        } catch (RouteNotFoundException $ex) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    protected function fail($other, $description, ComparisonFailure $comparisonFailure = null): void
    {
        /**
         * @var string $subdomain
         */
        $subdomain = $other['subdomain'];

        /**
         * @var string $url
         */
        $url = $other['url'];

        /**
         * @var Morphable $morphable
         */
        $morphable = $other['morphable'];

        throw new ExpectationFailedException(
            sprintf(
                'Failed asserting that route [subdomain=%s, url=%s] exists and points at morphable [id=%s, type=%s].',
                $subdomain,
                $url,
                $morphable->getMorphableId(),
                $morphable::getMorphableType()
            )
        );
    }

}
