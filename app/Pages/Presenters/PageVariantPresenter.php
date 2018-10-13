<?php

namespace App\Pages\Presenters;

use App\Core\Presenters\Presenter;
use App\Pages\Models\PageVariant;

final class PageVariantPresenter extends Presenter
{

    /**
     * @var PageVariant
     */
    protected $model;

    /**
     * @return string
     */
    public function getBadgeForStatus(): string
    {
        return array_get([
            PageVariant::STATUS_DRAFT => 'badge-warning',
            PageVariant::STATUS_PUBLISHED => 'badge-success',
            PageVariant::STATUS_DELETED => 'badge-danger',
        ], $this->model->status, '');
    }

}
