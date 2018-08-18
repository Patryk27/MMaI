<?php

namespace App\Routes\Queries;

/**
 * This class defines a query which will return all the routes with URL
 * partially matching given one.
 */
final class GetRoutesLikeUrlQuery implements RoutesQueryInterface
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