<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Core\Services\Searcher\SearcherInterface;
use App\Pages\Models\PageVariant;
use Illuminate\Support\Collection;

interface PageVariantsSearcherInterface extends SearcherInterface
{

    #region Inherited from SearcherInterface

    /**
     * @inheritdoc
     *
     * @return Collection|PageVariant[]
     */
    public function get(): Collection;

    #endregion

}