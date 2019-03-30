<?php

namespace App\Pages\Presenters;

use App\Core\Presenters\Presenter;
use App\Pages\Models\Page;

final class PagePresenter extends Presenter {

    /** @var Page */
    protected $model;

    /**
     * @return string
     */
    public function getTranslatedType(): string {
        return __('base/models/page.enums.type.' . $this->model->type);
    }

}
