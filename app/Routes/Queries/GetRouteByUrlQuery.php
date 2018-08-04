<?php

namespace App\Routes\Queries;

/**
 * This class defines a query which will return single route with specified URL.
 */
final class GetRouteByUrlQuery implements RoutesQueryInterface
{

    /**
     * @var string
     */
    private $url;

    /**
     * @param string $url
     */
    public function __construct(
        string $url
    ) {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

}