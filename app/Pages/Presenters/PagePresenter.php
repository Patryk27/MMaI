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

    /**
     * @return string
     */
    public function getStatusBadge(): string {
        return array_get([
            Page::STATUS_DRAFT => 'badge-warning',
            Page::STATUS_PUBLISHED => 'badge-success',
            Page::STATUS_DELETED => 'badge-danger',
        ], $this->model->status, '');
    }
}
