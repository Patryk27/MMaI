<?php

namespace App\Services\Core\Language;

use App\Exceptions\Exception as AppException;
use App\Models\Language;
use App\Repositories\LanguagesRepository;
use App\Repositories\RoutesRepository;
use Illuminate\Http\Request;

class Detector
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var RoutesRepository
     */
    private $routesRepository;

    /**
     * @var LanguagesRepository
     */
    private $languagesRepository;

    /**
     * @param Request $request
     * @param RoutesRepository $routesRepository
     * @param LanguagesRepository $languagesRepository
     */
    public function __construct(
        Request $request,
        RoutesRepository $routesRepository,
        LanguagesRepository $languagesRepository
    ) {
        $this->request = $request;
        $this->routesRepository = $routesRepository;
        $this->languagesRepository = $languagesRepository;
    }

    /**
     * @inheritdoc
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
     */
    private function detectByRoute(string $url): ?Language
    {
        $route = $this->routesRepository->getByUrl($url);

        if (empty($route)) {
            return null;
        }

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
     */
    private function detectByUrl(string $url): ?Language
    {
        $urlParts = explode('/', $url);

        return $this->languagesRepository->getBySlug($urlParts[0]);
    }

}