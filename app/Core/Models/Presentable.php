<?php

namespace App\Core\Models;

/**
 * This interface is used to mark model as "presentable", meaning that it has
 * its own presenter.
 */
interface Presentable {
    /**
     * Returns name of the presenter's class.
     *
     * @return string
     */
    public static function getPresenterClass(): string;
}
