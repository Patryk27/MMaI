<?php

namespace App\Core\Websites;

use App\Websites\Exceptions\WebsiteException;
use App\Websites\Exceptions\WebsiteNotFoundException;
use App\Websites\Models\Website;
use App\Websites\Queries\GetWebsiteBySlug;
use App\Websites\WebsitesFacade;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WebsiteDetector {

    /** @var WebsitesFacade */
    private $websitesFacade;

    public function __construct(WebsitesFacade $websitesFacade) {
        $this->websitesFacade = $websitesFacade;
    }

    /**
     * @param Request $request
     * @return Website|null
     * @throws WebsiteException
     */
    public function detect(Request $request): ?Website {
        try {
            $subdomain = $request->route('subdomain') ?? '';

            return $this->websitesFacade->queryOne(
                new GetWebsiteBySlug($subdomain)
            );
        } catch (WebsiteNotFoundException $ex) {
            return null;
        }
    }

    /**
     * @param Request $request
     * @return Website
     * @throws WebsiteException
     * @throws NotFoundHttpException
     */
    public function detectOrFail(Request $request): Website {
        $website = $this->detect($request);

        if (is_null($website)) {
            throw new NotFoundHttpException(sprintf(
                'Website [%s] does not exist.', $request->url()
            ));
        }

        return $website;
    }

}
