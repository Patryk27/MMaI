<?php

namespace App\Analytics\Implementation\Services;

use App\Analytics\Models\Event;
use App\Core\Searcher\Searcher;
use Illuminate\Support\Collection;

interface RequestEventsSearcher extends Searcher {
    #region Inherited from Searcher
    /**
     * @inheritdoc
     *
     * @return Collection|Event[]
     */
    public function get(): Collection;
    #endregion

}
