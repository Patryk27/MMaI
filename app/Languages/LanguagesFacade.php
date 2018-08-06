<?php

namespace App\Languages;

use App\Languages\Exceptions\LanguageNotFoundException;
use App\Languages\Implementation\Services\LanguagesQuerier;
use App\Languages\Models\Language;
use App\Languages\Queries\LanguagesQueryInterface;
use Illuminate\Support\Collection;

final class LanguagesFacade
{

    /**
     * @var LanguagesQuerier
     */
    private $languagesQuerier;

    /**
     * @param LanguagesQuerier $languagesQuerier
     */
    public function __construct(
        LanguagesQuerier $languagesQuerier
    ) {
        $this->languagesQuerier = $languagesQuerier;
    }

    /**
     * @param LanguagesQueryInterface $query
     * @return Language
     *
     * @throws LanguageNotFoundException
     */
    public function queryOne(LanguagesQueryInterface $query): Language
    {
        $languages = $this->queryMany($query);

        if ($languages->isEmpty()) {
            throw new LanguageNotFoundException();
        }

        return $languages->first();
    }

    /**
     * @param LanguagesQueryInterface $query
     * @return Collection|Language[]
     */
    public function queryMany(LanguagesQueryInterface $query): Collection
    {
        return $this->languagesQuerier->query($query);
    }

}