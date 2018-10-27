<?php

namespace App\Languages;

use App\Languages\Implementation\Repositories\LanguagesRepository;
use App\Languages\Implementation\Services\LanguagesQuerier;

final class LanguagesFactory
{

    /**
     * Builds an instance of @see LanguagesFacade.
     *
     * @param LanguagesRepository $languagesRepository
     * @return LanguagesFacade
     */
    public static function build(
        LanguagesRepository $languagesRepository
    ): LanguagesFacade {
        $languagesQuerier = new LanguagesQuerier($languagesRepository);

        return new LanguagesFacade(
            $languagesQuerier
        );
    }

}
