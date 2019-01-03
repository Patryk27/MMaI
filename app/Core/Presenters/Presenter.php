<?php

namespace App\Core\Presenters;

use Illuminate\Database\Eloquent\Model;

abstract class Presenter
{
    /** @var Model */
    protected $model;

    /**
     * @param Model $model
     * @return void
     */
    public function setModel(Model $model): void
    {
        $this->model = $model;
    }
}
