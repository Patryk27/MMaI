<?php

namespace App\IntrinsicPages\Implementation\Services;

use App\IntrinsicPages\Models\IntrinsicPage;
use LogicException;

class IntrinsicPagesRenderer
{

    /**
     * @var Renderers\RendererInterface[]
     */
    private $renderers;

    /**
     * @param Renderers\HomeRenderer $homeRenderer
     * @param Renderers\SearchRenderer $searchRenderer
     */
    public function __construct(
        Renderers\HomeRenderer $homeRenderer,
        Renderers\SearchRenderer $searchRenderer
    ) {
        $this->renderers = [
            IntrinsicPage::TYPE_HOME => $homeRenderer,
            IntrinsicPage::TYPE_SEARCH => $searchRenderer,
        ];
    }

    /**
     * Returns given internal page and returns its response.
     *
     * Returned value may be a string value (i.e. a rendered view) or any
     * other response handleable by Laravel (i.e. a redirection).
     *
     * @param IntrinsicPage $intrinsicPage
     * @return mixed
     */
    public function render(IntrinsicPage $intrinsicPage)
    {
        $renderer = $this->renderers[$intrinsicPage->type] ?? null;

        if (is_null($renderer)) {
            throw new LogicException(
                sprintf('Found no renderer for internal page of type [%s].', $intrinsicPage->type)
            );
        }

        return $renderer->render();
    }

}