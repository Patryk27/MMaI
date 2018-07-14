<?php

namespace App\Services\InternalPages;

use App\Models\InternalPage;
use LogicException;

class Renderer
{

    /**
     * @var Renderers\RendererInterface[]
     */
    private $renderers;

    /**
     * @param Renderers\HomeRenderer $homePageRenderer
     */
    public function __construct(
        Renderers\HomeRenderer $homePageRenderer
    ) {
        $this->renderers = [
            InternalPage::TYPE_HOME => $homePageRenderer,
        ];
    }

    /**
     * Returns given internal page and returns its contents.
     *
     * @param InternalPage $internalPage
     * @return mixed
     */
    public function render(InternalPage $internalPage)
    {
        $renderer = $this->renderers[$internalPage->type] ?? null;

        if (is_null($renderer)) {
            throw new LogicException(
                sprintf('Found no renderer for internal page of type [%s].', $internalPage->type)
            );
        }

        return $renderer->render();
    }

}