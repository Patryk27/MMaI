<?php

namespace Tests\Constraints\Routes;

use App\Core\Models\Morphable;
use App\Routes\Exceptions\RouteException;
use App\Routes\Queries\GetRouteBySubdomainAndUrl;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

final class RoutePointsAt extends Constraint {

    /**
     * @inheritdoc
     * @param Morphable $other
     * @return bool
     * @throws RouteException
     */
    public function matches($other): bool {
        $subdomain = $other['subdomain'];
        $url = $other['url'];

        /** @var Morphable $model */
        $model = $other['model'];

        $route = $this->routesFacade->queryOne(
            new GetRouteBySubdomainAndUrl($subdomain, $url)
        );

        return $route->model_id === $model->getMorphableId()
            && $route->model_type === $model::getMorphableType();
    }

    /**
     * @inheritdoc
     */
    protected function fail($other, $description, ComparisonFailure $comparisonFailure = null): void {
        $subdomain = $other['subdomain'];
        $url = $other['url'];

        /** @var Morphable $model */
        $model = $other['model'];

        throw new ExpectationFailedException(sprintf(
            'Failed asserting that route [subdomain=%s, url=%s] points at [id=%s, type=%s].',
            $subdomain,
            $url,
            $model->getMorphableId(),
            $model::getMorphableType()
        ));
    }

}
