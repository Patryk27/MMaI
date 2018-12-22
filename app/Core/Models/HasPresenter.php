<?php

namespace App\Core\Models;

use App\Core\Presenters\Presenter;

trait HasPresenter
{
    /** @var Presenter|null */
    private $presenterInstance;

    /**
     * @return Presenter
     */
    public function getPresenter(): Presenter
    {
        if (!isset($this->presenterInstance)) {
            $this->presenterInstance = app(
                $this->getPresenterClass()
            );

            /** @noinspection PhpParamsInspection */
            $this->presenterInstance->setModel($this);
        }

        return $this->presenterInstance;
    }
}
