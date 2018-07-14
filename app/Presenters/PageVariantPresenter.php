<?php

namespace App\Presenters;

use App\Models\PageVariant;

class PageVariantPresenter extends Presenter
{

    /**
     * @var PageVariant
     */
    protected $model;

    /**
     * @return string
     */
    public function getStatusBadgeClass(): string
    {
        return array_get([
            PageVariant::STATUS_DRAFT => 'badge-warning',
            PageVariant::STATUS_PUBLISHED => 'badge-success',
            PageVariant::STATUS_DELETED => 'badge-danger',
        ], $this->model->status, '');
    }

}