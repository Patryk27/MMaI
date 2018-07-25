<?php

namespace App\Routes\Queries;

class GetRouteByUrlQuery implements RouteQueryInterface
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