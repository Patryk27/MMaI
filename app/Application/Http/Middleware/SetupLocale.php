<?php

namespace App\Application\Http\Middleware;

use App\Core\Websites\WebsiteDetector;
use App\Websites\Exceptions\WebsiteException;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;

final class SetupLocale {
    /** @var Translator */
    private $translator;

    /** @var WebsiteDetector */
    private $websiteDetector;

    public function __construct(
        Translator $translator,
        WebsiteDetector $websiteDetector
    ) {
        $this->translator = $translator;
        $this->websiteDetector = $websiteDetector;
    }

    /**
     * @param Request $request
     * @param callable $next
     * @return mixed
     * @throws WebsiteException
     */
    public function handle(Request $request, callable $next) {
        if ($website = $this->websiteDetector->detect($request)) {
            $this->translator->setLocale($website->language->iso639_code);
        }

        return $next($request);
    }
}
