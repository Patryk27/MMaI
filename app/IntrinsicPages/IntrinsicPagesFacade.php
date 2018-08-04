<?php

namespace App\IntrinsicPages;

use App\IntrinsicPages\Implementation\Services\IntrinsicPagesRenderer;
use App\IntrinsicPages\Models\IntrinsicPage;

final class IntrinsicPagesFacade
{

    /**
     * @var IntrinsicPagesRenderer
     */
    private $intrinsicPagesRenderer;

    /**
     * @param IntrinsicPagesRenderer $intrinsicPagesRenderer
     */
    public function __construct(
        IntrinsicPagesRenderer $intrinsicPagesRenderer
    ) {
        $this->intrinsicPagesRenderer = $intrinsicPagesRenderer;
    }

    /**
     * @param IntrinsicPage $intrinsicPage
     * @return mixed
     */
    public function render(IntrinsicPage $intrinsicPage)
    {
        return $this->intrinsicPagesRenderer->render($intrinsicPage);
    }

}