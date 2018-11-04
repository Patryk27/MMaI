<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Core\Collection\Paginator as CollectionPaginator;
use App\Core\Exceptions\Exception as CoreException;
use App\Core\Language\Detector as LanguageDetector;
use App\Languages\Exceptions\LanguageException;
use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPageVariantsQuery;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    private const NUMBER_OF_POSTS_PER_PAGE = 10;

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
     * @param Request $request
     * @return mixed
     *
     * @throws CoreException
     * @throws PageException
     * @throws LanguageException
     *
     * @todo a bit too much is happening in here
     */
    public function index(Request $request)
    {
        $language = $this->languageDetector->detectOrFail($request);

        $query = [
            'filters' => [
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

        return view('frontend.views.home', [
            'posts' => $posts,
        ]);
    }

}
