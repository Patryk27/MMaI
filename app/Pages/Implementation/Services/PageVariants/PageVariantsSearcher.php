<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Core\Searcher\Searcher;
use App\Pages\Models\PageVariant;
use Illuminate\Support\Collection;

interface PageVariantsSearcher extends Searcher
{

    #region Inherited from Searcher

    /**
     * @inheritdoc
     *
     * @return Collection|PageVariant[]
     */
    public function get(): Collection;

    #endregion

}
