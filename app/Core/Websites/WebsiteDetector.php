<?php

namespace App\Core\Websites;

use App\Core\Exceptions\Exception as CoreException;
use App\Websites\Exceptions\WebsiteException;
use App\Websites\Exceptions\WebsiteNotFoundException;
use App\Websites\Models\Website;
use App\Websites\Queries\GetWebsiteBySlugQuery;
use App\Websites\WebsitesFacade;
use Illuminate\Http\Request;

class WebsiteDetector
{
    /** @var WebsitesFacade */
    private $websitesFacade;

    public function __construct(WebsitesFacade $websitesFacade)
    {
        $this->websitesFacade = $websitesFacade;
    }

    /**
     * @param Request $request
     * @return Website|null
     * @throws WebsiteException
     */
    public function detect(Request $request): ?Website
    {
        try {
            $subdomain = $request->route('subdomain');

            return $this->websitesFacade->queryOne(
                new GetWebsiteBySlugQuery($subdomain)
            );
        } catch (WebsiteNotFoundException $ex) {
            return null;
        }
    }

    /**
     * @param Request $request
     * @return Website
     * @throws CoreException
     * @throws WebsiteException
     */
    public function detectOrFail(Request $request): Website
    {
        $website = $this->detect($request);

        if (is_null($website)) {
            throw new CoreException(sprintf(
                'Failed to detect website for URL [%s].', $request->url()
            ));
        }

        return $website;
    }
}
