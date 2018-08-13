<?php

namespace App\IntrinsicPages\Implementation\Services;

use App\IntrinsicPages\Exceptions\IntrinsicPageException;
use App\IntrinsicPages\Models\IntrinsicPage;

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

    /***
     * @param IntrinsicPage $intrinsicPage
     * @return mixed
     *
     * @throws IntrinsicPageException
     */
    public function render(IntrinsicPage $intrinsicPage)
    {
        $renderer = $this->renderers[$intrinsicPage->type] ?? null;

        if (is_null($renderer)) {
            throw new IntrinsicPageException(
                sprintf('Found no renderer for intrinsic page of type [%s].', $intrinsicPage->type)
            );
        }

        return $renderer->render();
    }

}