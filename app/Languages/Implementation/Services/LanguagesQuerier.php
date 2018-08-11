<?php

namespace App\Languages\Implementation\Services;

use App\Languages\Implementation\Repositories\LanguagesRepositoryInterface;
use App\Languages\Models\Language;
use App\Languages\Queries\GetAllLanguagesQuery;
use App\Languages\Queries\GetLanguageByIdQuery;
use App\Languages\Queries\GetLanguageBySlugQuery;
use App\Languages\Queries\LanguagesQueryInterface;
use Illuminate\Support\Collection;
use LogicException;

class LanguagesQuerier
{

    /**
     * @var LanguagesRepositoryInterface
     */
    private $languagesRepository;

    /**
     * @param LanguagesRepositoryInterface $languagesRepository
     */
    public function __construct(
        LanguagesRepositoryInterface $languagesRepository
    ) {
        $this->languagesRepository = $languagesRepository;
    }

    /**
     * @param LanguagesQueryInterface $query
     * @return Collection|Language[]
     */
    public function query(LanguagesQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof GetAllLanguagesQuery:
                return $this->languagesRepository->getAll();

            case $query instanceof GetLanguageByIdQuery:
                return collect_one(
                    $this->languagesRepository->getById(
                        $query->getId()
                    )
                );

            case $query instanceof GetLanguageBySlugQuery:
                return collect_one(
                    $this->languagesRepository->getBySlug(
                        $query->getSlug()
                    )
                );

            default:
                throw new LogicException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

}