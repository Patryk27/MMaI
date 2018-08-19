<?php

namespace App\Routes\Queries;

/**
 * This class defines a query which will return a single route with matching
 * subdomain and URL.
 */
final class GetRouteBySubdomainAndUrlQuery implements RoutesQueryInterface
{

    /**
     * @var string
     */
    private $subdomain;

    /**
     * @var string
     */
    private $url;

    /**
     * @param string $subdomain
     * @param string $url
     */
    public function __construct(
        string $subdomain,
        string $url
    ) {
        $this->subdomain = $subdomain;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getSubdomain(): string
    {
        return $this->subdomain;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

}