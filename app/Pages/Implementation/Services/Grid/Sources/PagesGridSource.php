<?php

namespace App\Pages\Implementation\Services\Grid\Sources;

use App\Grid\Sources\GridSource;
use App\Pages\Models\Page;
use Illuminate\Support\Collection;

interface PagesGridSource extends GridSource {

    #region Inherited from GridSource

    /**
     * @return Collection|Page[]
     */
    public function get(): Collection;

    #endregion

}
