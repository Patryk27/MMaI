<?php

namespace Tests\Assertions\Routes;

use App\Core\Models\Interfaces\Morphable;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Queries\GetRouteByUrlQuery;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

class RoutePointsAtAssertion extends Constraint
{

    /**
     * @inheritdoc
     *
     * @param Morphable $other
     */
    public function matches($other): bool
    {
        try {
            /**
             * @var string $url
             */
            $url = $other['url'];

            /**
             * @var Morphable $morphable
             */
            $morphable = $other['morphable'];

            $route = $this->routesFacade->queryOne(
                new GetRouteByUrlQuery($url)
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
         * @var string $url
         */
        $url = $other['url'];

        /**
         * @var Morphable $morphable
         */
        $morphable = $other['morphable'];

        throw new ExpectationFailedException(
            sprintf('Failed asserting that route with url [%s] exists and points at morphable [id=%s, type=%s].', $url, $morphable->getMorphableId(), $morphable::getMorphableType())
        );
    }

}
