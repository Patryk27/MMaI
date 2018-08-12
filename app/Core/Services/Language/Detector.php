<?php

namespace App\Core\Services\Language;

use App\Core\Exceptions\Exception as AppException;
use App\Languages\Exceptions\LanguageException;
use App\Languages\LanguagesFacade;
use App\Languages\Models\Language;
use App\Languages\Queries\GetLanguageBySlugQuery;
use App\Routes\Exceptions\RouteException;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteByUrlQuery;
use App\Routes\RoutesFacade;
use Illuminate\Http\Request;

class Detector
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var LanguagesFacade
     */
    private $languagesFacade;

    /**
     * @var RoutesFacade
     */
    private $routesFacade;

    /**
     * @param Request $request
     * @param LanguagesFacade $languagesFacade
     * @param RoutesFacade $routesFacade
     */
    public function __construct(
        Request $request,
        LanguagesFacade $languagesFacade,
        RoutesFacade $routesFacade
    ) {
        $this->request = $request;
        $this->languagesFacade = $languagesFacade;
        $this->routesFacade = $routesFacade;
    }

    /**
     * @inheritdoc
     *
     * @throws RouteException
     * @throws LanguageException
     */
    public function getLanguage(): ?Language
    {
        $url = $this->request->route('url');

        // No `url` means we cannot detect anything - bail out immediately.
        if (empty($url)) {
            return null;
        }

        return
            $this->detectByRoute($url) ??
            $this->detectByUrl($url);
    }

    /**
     * @inheritdoc
     *
     * @throws AppException
     */
    public function getLanguageOrFail(): Language
    {
        $language = $this->getLanguage();

        if (is_null($language)) {
            throw new AppException(
                sprintf('Failed to detect language for URL [%s].', $this->request->url())
            );
        }

        return $language;
    }

    /**
     * This method tries to detect language by analyzing model at which given
     * URL points at.
     *
     * If, for example, given URL points at a @see PageVariant, we may utilize
     * its "language" property and return it.
     *
     * @param string $url
     * @return Language|null
     *
     * @throws RouteException
     */
    private function detectByRoute(string $url): ?Language
    {
        $routes = $this->routesFacade->queryMany(
            new GetRouteByUrlQuery($url)
        );

        if ($routes->isEmpty()) {
            return null;
        }

        /**
         * @var Route $route
         */
        $route = $routes->first();

        /** @noinspection PhpUndefinedFieldInspection */
        if (isset($route->model->language)) {
            /** @noinspection PhpUndefinedFieldInspection */
            return $route->model->language;
        }

        return null;
    }

    /**
     * This method tries to detect language by analyzing the URL itself.
     *
     * If, for example, given URL is "en/something", we may deduce that the "en"
     * part refers to the language name.
     *
     * @param string $url
     * @return Language|null
     *
     * @throws LanguageException
     */
    private function detectByUrl(string $url): ?Language
    {
        $urlParts = explode('/', $url);

        $languages = $this->languagesFacade->queryMany(
            new GetLanguageBySlugQuery($urlParts[0])
        );

        return $languages->first();
    }

}