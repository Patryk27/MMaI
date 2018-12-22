<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Core\Collection\Paginator as CollectionPaginator;
use App\Core\Exceptions\Exception as CoreException;
use App\Core\Language\Detector as LanguageDetector;
use App\Languages\Exceptions\LanguageException;
use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPages;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private const NUMBER_OF_ITEMS_PER_PAGE = 10;

    /** @var ViewFactoryContract */
    private $viewFactory;

    /** @var CollectionPaginator */
    private $paginator;

    /** @var LanguageDetector */
    private $languageDetector;

    /** @var PagesFacade */
    private $pagesFacade;

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
     * @throws CoreException
     * @throws PageException
     * @throws LanguageException
     *
     * @todo a bit too much happens in here
     */
    public function index(Request $request)
    {
        $language = $this->languageDetector->detectOrFail($request);

        $query = [
            'filters' => [
                SearchPages::FIELD_STATUS => [
                    'operator' => 'expression',
                    'value' => Page::STATUS_PUBLISHED,
                ],

                SearchPages::FIELD_TYPE => [
                    'operator' => 'expression',
                    'value' => Page::TYPE_POST,
                ],

                SearchPages::FIELD_LANGUAGE_ID => [
                    'operator' => 'expression',
                    'value' => $language->id,
                ],
            ],

            'orderBy' => [
                SearchPages::FIELD_ID => 'desc',
            ],
        ];

        $numberOfPages = $this->pagesFacade->queryCount(
            new SearchPages($query)
        );

        $query['pagination'] = [
            'page' => $this->paginator->getCurrentPageNumber(),
            'perPage' => self::NUMBER_OF_ITEMS_PER_PAGE,
        ];

        $pages = $this->pagesFacade->queryMany(
            new SearchPages($query)
        );

        $renderedPages = $pages->map([$this->pagesFacade, 'render']);

        $renderedPages = $this->paginator->build(
            $renderedPages,
            $numberOfPages,
            self::NUMBER_OF_ITEMS_PER_PAGE
        );

        return view('frontend.views.home', [
            'renderedPages' => $renderedPages,
        ]);
    }
}
