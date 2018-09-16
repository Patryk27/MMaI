<?php

namespace App\Languages;

use App\Languages\Implementation\Repositories\LanguagesRepositoryInterface;
use App\Languages\Implementation\Services\LanguagesQuerier;

final class LanguagesFactory
{

    /**
     * Builds an instance of @see LanguagesFacade.
     *
     * @param LanguagesRepositoryInterface $languagesRepository
     * @return LanguagesFacade
     */
    public static function build(
        LanguagesRepositoryInterface $languagesRepository
    ): LanguagesFacade {
        $languagesQuerier = new LanguagesQuerier($languagesRepository);

        return new LanguagesFacade(
            $languagesQuerier
        );
    }

}
