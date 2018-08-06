<?php

namespace App\IntrinsicPages;

use App\Core\Services\Collection\Paginator as CollectionPaginator;
use App\Core\Services\Language\Detector as LanguageDetector;
use App\IntrinsicPages\Implementation\Services\IntrinsicPagesRenderer;
use App\IntrinsicPages\Implementation\Services\Renderers\HomeRenderer;
use App\IntrinsicPages\Implementation\Services\Renderers\SearchRenderer;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;

final class IntrinsicPagesFactory
{

    /**
     * Builds an instance of @see IntrinsicPagesFacade.
     *
     * @param ViewFactoryContract $viewFactory
     * @param CollectionPaginator $collectionPaginator
     * @param LanguageDetector $languageDetector
     * @return IntrinsicPagesFacade
     */
    public static function build(
        ViewFactoryContract $viewFactory,
        CollectionPaginator $collectionPaginator,
        LanguageDetector $languageDetector
    ): IntrinsicPagesFacade {
        $homeRenderer = new HomeRenderer($viewFactory, $collectionPaginator, $languageDetector);
        $searchRenderer = new SearchRenderer($viewFactory);

        $intrinsicPagesRenderer = new IntrinsicPagesRenderer($homeRenderer, $searchRenderer);

        return new IntrinsicPagesFacade(
            $intrinsicPagesRenderer
        );
    }

}