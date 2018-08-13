<?php

namespace App\IntrinsicPages;

use App\IntrinsicPages\Exceptions\IntrinsicPageException;
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
     * Returns given intrinsic page and returns its response.
     *
     * Returned value may be a string value (i.e. a rendered view) or any
     * other response handleable by Laravel (i.e. a redirection).
     *
     * @param IntrinsicPage $intrinsicPage
     * @return mixed
     *
     * @throws IntrinsicPageException
     */
    public function render(IntrinsicPage $intrinsicPage)
    {
        return $this->intrinsicPagesRenderer->render($intrinsicPage);
    }

}