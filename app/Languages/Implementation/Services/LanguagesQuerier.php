<?php

namespace App\Languages\Implementation\Services;

use App\Languages\Exceptions\LanguageException;
use App\Languages\Implementation\Repositories\LanguagesRepository;
use App\Languages\Models\Language;
use App\Languages\Queries\GetAllLanguages;
use App\Languages\Queries\GetLanguageById;
use App\Languages\Queries\LanguagesQuery;
use Illuminate\Support\Collection;

class LanguagesQuerier {

    /** @var LanguagesRepository */
    private $languagesRepository;

    public function __construct(LanguagesRepository $languagesRepository) {
        $this->languagesRepository = $languagesRepository;
    }

    /**
     * @param LanguagesQuery $query
     * @return Collection|Language[]
     * @throws LanguageException
     */
    public function query(LanguagesQuery $query): Collection {
        switch (true) {
            case $query instanceof GetAllLanguages:
                return $this->languagesRepository->getAll();

            case $query instanceof GetLanguageById:
                return collect_one(
                    $this->languagesRepository->getById(
                        $query->getId()
                    )
                );

            default:
                throw new LanguageException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }

}
