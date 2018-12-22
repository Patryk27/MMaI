<?php

namespace App\Core\Language;

use App\Core\Exceptions\Exception as CoreException;
use App\Languages\Exceptions\LanguageException;
use App\Languages\Exceptions\LanguageNotFoundException;
use App\Languages\LanguagesFacade;
use App\Languages\Models\Language;
use App\Languages\Queries\GetLanguageBySlugQuery;
use Illuminate\Http\Request;

class Detector
{
    /**
     * @var LanguagesFacade
     */
    private $languagesFacade;

    public function __construct(LanguagesFacade $languagesFacade)
    {
        $this->languagesFacade = $languagesFacade;
    }

    /**
     * @param Request $request
     * @return Language|null
     * @throws LanguageException
     */
    public function detect(Request $request): ?Language
    {
        try {
            $subdomain = $request->route('subdomain');

            return $this->languagesFacade->queryOne(
                new GetLanguageBySlugQuery($subdomain ?? 'en')
            );
        } catch (LanguageNotFoundException $ex) {
            return null;
        }
    }

    /**
     * @param Request $request
     * @return Language
     * @throws CoreException
     * @throws LanguageException
     */
    public function detectOrFail(Request $request): Language
    {
        $language = $this->detect($request);

        if (is_null($language)) {
            throw new CoreException(sprintf(
                'Failed to detect language for URL [%s].', $request->url()
            ));
        }

        return $language;
    }
}
