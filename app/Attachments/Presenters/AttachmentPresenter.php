<?php

namespace App\Attachments\Presenters;

use App\Attachments\Models\Attachment;
use App\Core\Presenters\Presenter;

final class AttachmentPresenter extends Presenter
{

    /**
     * @var Attachment
     */
    protected $model;

    /**
     * @return string
     */
    public function getBackendDownloadUrl(): string
    {
        return route('backend.attachments.download', $this->model->path);
    }

    /**
     * @return string
     */
    public function getFrontendDownloadUrl(): string
    {
        return route('frontend.attachments.download', $this->model->path);
    }

}
