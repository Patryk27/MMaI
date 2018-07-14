<?php

namespace App\Services\InternalPages\Renderers;

use App\Models\Page;
use App\Models\PageVariant;
use App\Services\Core\Collection\Paginator as CollectionPaginator;
use App\Services\Core\Language\Detector as LanguageDetector;
use App\Services\PageVariants\Renderer as PageVariantsRenderer;
use App\Services\PageVariants\Searcher as PageVariantsSearcher;
use Exception;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;

class HomeRenderer implements RendererInterface
{

    const NUMBER_OF_POSTS_PER_PAGE = 10;

    /**
     * @var ViewFactoryContract
     */
    private $viewFactory;

    /**
     * @var CollectionPaginator
     */
    private $paginator;

    /**
     * @var LanguageDetector
     */
    private $languageDetector;

    /**
     * @var PageVariantsSearcher
     */
    private $pageVariantsSearcher;

    /**
     * @var PageVariantsRenderer
     */
    private $pageVariantsRenderer;

    /**
     * @param ViewFactoryContract $viewFactory
     * @param CollectionPaginator $paginator
     * @param LanguageDetector $languageDetector
     * @param PageVariantsSearcher $pageVariantsSearcher
     * @param PageVariantsRenderer $pageVariantsRenderer
     */
    public function __construct(
        ViewFactoryContract $viewFactory,
        CollectionPaginator $paginator,
        LanguageDetector $languageDetector,
        PageVariantsSearcher $pageVariantsSearcher,
        PageVariantsRenderer $pageVariantsRenderer
    ) {
        $this->viewFactory = $viewFactory;
        $this->paginator = $paginator;
        $this->languageDetector = $languageDetector;
        $this->pageVariantsSearcher = $pageVariantsSearcher;
        $this->pageVariantsRenderer = $pageVariantsRenderer;
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    public function render()
    {
        // @todo there's too much happening in here

        $language = $this->languageDetector->getLanguageOrFail();

        $this->pageVariantsSearcher->filter([
            PageVariantsSearcher::FIELD_STATUS => PageVariant::STATUS_PUBLISHED,
            PageVariantsSearcher::FIELD_PAGE_TYPE => Page::TYPE_BLOG,
            PageVariantsSearcher::FIELD_LANGUAGE_ID => $language->id,
        ]);

        $this->pageVariantsSearcher->orderBy(PageVariantsSearcher::FIELD_ID, false);

        $totalNumberOfPages = $this->pageVariantsSearcher->getCount();

        $this->pageVariantsSearcher->forPage(
            $this->paginator->getCurrentPageNumber(),
            self::NUMBER_OF_POSTS_PER_PAGE
        );

        $pageVariants = $this->pageVariantsSearcher->get();
        $pageVariants = $this->pageVariantsRenderer->renderMany($pageVariants);
        $pageVariants = $this->paginator->build($pageVariants, $totalNumberOfPages, self::NUMBER_OF_POSTS_PER_PAGE);

        $view = $this->viewFactory->make('frontend.internal-pages.home', [
            'posts' => $pageVariants,
        ]);

        return $view->render();
    }

}