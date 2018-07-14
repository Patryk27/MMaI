<?php

namespace App\Services\InternalPages\Renderers;

interface RendererInterface
{

    /**
     * Renders special page and returns its contents.
     *
     * @return mixed
     */
    public function render();

}