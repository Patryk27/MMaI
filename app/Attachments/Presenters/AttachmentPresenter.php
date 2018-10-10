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
    public function getUrl(): string
    {
        return route('frontend.attachments.download', [
            'path' => $this->model->path,
        ]);
    }

}
