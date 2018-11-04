<?php

namespace App\Application\Http\Middleware;

use App\Core\Language\Detector as LanguageDetector;
use App\Languages\Exceptions\LanguageException;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;

final class SetLocale
{

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var LanguageDetector
     */
    private $languageDetector;

    /**
     * @param Translator $translator
     * @param LanguageDetector $languageDetector
     */
    public function __construct(
        Translator $translator,
        LanguageDetector $languageDetector
    ) {
        $this->translator = $translator;
        $this->languageDetector = $languageDetector;
    }

    /**
     * @param Request $request
     * @param callable $next
     * @return mixed
     * @throws LanguageException
     */
    public function handle(Request $request, callable $next)
    {
        if ($language = $this->languageDetector->detect($request)) {
            $this->translator->setLocale($language->iso_639_code);
        }

        return $next($request);
    }

}
