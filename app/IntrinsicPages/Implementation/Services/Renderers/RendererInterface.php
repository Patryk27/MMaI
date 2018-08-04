<?php

namespace App\IntrinsicPages\Implementation\Services\Renderers;

interface RendererInterface
{

    /**
     * Renders special page and returns its contents.
     *
     * @return mixed
     */
    public function render();

}