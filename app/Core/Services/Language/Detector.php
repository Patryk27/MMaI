<?php

namespace App\Core\Services\Language;

use App\Core\Exceptions\Exception as AppException;
use App\Languages\Exceptions\LanguageException;
use App\Languages\Exceptions\LanguageNotFoundException;
use App\Languages\LanguagesFacade;
use App\Languages\Models\Language;
use App\Languages\Queries\GetLanguageBySlugQuery;
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
     * @throws LanguageException
     */
    public function getLanguage(): ?Language
    {
        try {
            $subdomain = $this->request->route('subdomain');

            return $this->languagesFacade->queryOne(
                new GetLanguageBySlugQuery($subdomain ?? 'en')
            );
        } catch (LanguageNotFoundException $ex) {
            return null;
        }
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

}
