<?php

namespace App\Tags\Implementation\Services;

use App\Core\Searcher\Searcher;
use App\Tags\Models\Tag;
use Illuminate\Support\Collection;

interface TagsSearcher extends Searcher
{
    #region Inherited from Searcher
    /**
     * @inheritdoc
     * @return Collection|Tag[]
     */
    public function get(): Collection;
    #endregion
}
