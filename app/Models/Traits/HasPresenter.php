<?php

namespace App\Models\Traits;

use App\Presenters\Presenter;

trait HasPresenter
{

    /**
     * @var Presenter
     */
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