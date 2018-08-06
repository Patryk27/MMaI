<?php

namespace App\Tags\Implementation\Services;

use App\Core\Services\Searcher\SearcherInterface;
use App\Tags\Models\Tag;
use Illuminate\Support\Collection;

interface TagsSearcherInterface extends SearcherInterface
{

    #region Inherited from SearcherInterface

    /**
     * @inheritdoc
     *
     * @return Collection|Tag[]
     */
    public function get(): Collection;

    #endregion

}