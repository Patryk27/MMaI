<?php

namespace App\Languages;

use App\Languages\Exceptions\LanguageException;
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
     * Returns the first language matching given query.
     * Throws an exception if no such language exists.
     *
     * @param LanguagesQueryInterface $query
     * @return Language
     *
     * @throws LanguageException
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
     * Returns all languages matching given query.
     *
     * @param LanguagesQueryInterface $query
     * @return Collection|Language[]
     *
     * @throws LanguageException
     */
    public function queryMany(LanguagesQueryInterface $query): Collection
    {
        return $this->languagesQuerier->query($query);
    }

}
