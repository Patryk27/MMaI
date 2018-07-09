<?php

namespace App\Services\CustomPages\Handlers;

interface HandlerInterface
{

    /**
     * Renders special page.
     * Returns a controller response (e.g. a view or a redirection).
     *
     * @return mixed
     */
    public function render();

}