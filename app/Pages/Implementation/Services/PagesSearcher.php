<?php

namespace App\Pages\Implementation\Services;

use App\Core\Searcher\Searcher;
use App\Pages\Models\Page;
use Illuminate\Support\Collection;

interface PagesSearcher extends Searcher {
    #region Inherited from Searcher
    /**
     * @inheritdoc
     * @return Collection|Page[]
     */
    public function get(): Collection;
    #endregion

}
