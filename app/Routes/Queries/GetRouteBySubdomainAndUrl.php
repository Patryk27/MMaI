<?php

namespace App\Routes\Queries;

use Illuminate\Http\Request;

final class GetRouteBySubdomainAndUrl implements RoutesQuery {

    /** @var string */
    private $subdomain;

    /** @var string */
    private $url;

    public function __construct(string $subdomain, string $url) {
        $this->subdomain = $subdomain;
        $this->url = $url;
    }

    /**
     * @param Request $request
     * @return GetRouteBySubdomainAndUrl
     */
    public static function buildFromRequest(Request $request): self {
        return new self(
            $request->route('subdomain', ''),
            $request->route('url', '')
        );
    }

    /**
     * @return string
     */
    public function getSubdomain(): string {
        return $this->subdomain;
    }

    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }

}
