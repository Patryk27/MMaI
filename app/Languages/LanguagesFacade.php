<?php

namespace App\Languages;

use App\Languages\Models\Language;
use Illuminate\Support\Collection;

class LanguagesFacade
{

    /**
     * @param Queries\LanguageQueryInterface $query
     * @return Language
     */
    public function queryOne(Queries\LanguageQueryInterface $query): Language
    {
        unimplemented();
    }

    /**
     * @param Queries\LanguageQueryInterface $query
     * @return Collection|Language[]
     */
    public function queryMany(Queries\LanguageQueryInterface $query): Collection
    {
        unimplemented();
    }

}