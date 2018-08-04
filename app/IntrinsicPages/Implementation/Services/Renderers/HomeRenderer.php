<?php

namespace App\IntrinsicPages\Implementation\Services\Renderers;

use App\Core\Services\Collection\Paginator as CollectionPaginator;
use App\Core\Services\Language\Detector as LanguageDetector;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPageVariantsQuery;
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
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @param ViewFactoryContract $viewFactory
     * @param CollectionPaginator $paginator
     * @param LanguageDetector $languageDetector
     * @param PagesFacade $pagesFacade
     */
    public function __construct(
        ViewFactoryContract $viewFactory,
        CollectionPaginator $paginator,
        LanguageDetector $languageDetector,
        PagesFacade $pagesFacade
    ) {
        $this->viewFactory = $viewFactory;
        $this->paginator = $paginator;
        $this->languageDetector = $languageDetector;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @inheritdoc
     *
     * @throws Exception
     */
    public function render()
    {
        $language = $this->languageDetector->getLanguageOrFail();

        $query = [
            'filter' => [
                SearchPageVariantsQuery::FIELD_STATUS => PageVariant::STATUS_PUBLISHED,
                SearchPageVariantsQuery::FIELD_PAGE_TYPE => Page::TYPE_BLOG,
                SearchPageVariantsQuery::FIELD_LANGUAGE_ID => $language->id,
            ],

            'orderBy' => [
                SearchPageVariantsQuery::FIELD_ID => 'desc',
            ],
        ];

        $numberOfPosts = $this->pagesFacade->queryCount(
            new SearchPageVariantsQuery($query)
        );

        $query['pagination'] = [
            'page' => $this->paginator->getCurrentPageNumber(),
            'perPage' => self::NUMBER_OF_POSTS_PER_PAGE,
        ];

        $posts = $this->pagesFacade->queryMany(
            new SearchPageVariantsQuery($query)
        );

        $posts = $posts->map([$this->pagesFacade, 'render']);
        $posts = $this->paginator->build($posts, $numberOfPosts, self::NUMBER_OF_POSTS_PER_PAGE);

        $view = $this->viewFactory->make('frontend.internal-pages.home', [
            'posts' => $posts,
        ]);

        return $view->render();
    }

}