<?php

namespace App\Application\Interfaces\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Core\Exceptions\Exception as CoreException;
use App\Core\Paginator as CollectionPaginator;
use App\Core\Websites\WebsiteDetector;
use App\Pages\Exceptions\PageException;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPages;
use App\Websites\Exceptions\WebsiteException;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;

class HomeController extends Controller {

    private const NUMBER_OF_ITEMS_PER_PAGE = 10;

    /** @var ViewFactory */
    private $viewFactory;

    /** @var CollectionPaginator */
    private $paginator;

    /** @var WebsiteDetector */
    private $websiteDetector;

    /** @var PagesFacade */
    private $pagesFacade;

    public function __construct(
        ViewFactory $viewFactory,
        CollectionPaginator $paginator,
        WebsiteDetector $websiteDetector,
        PagesFacade $pagesFacade
    ) {
        $this->viewFactory = $viewFactory;
        $this->paginator = $paginator;
        $this->websiteDetector = $websiteDetector;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CoreException
     * @throws PageException
     * @throws WebsiteException
     *
     * @todo a bit too much happens in here
     */
    public function index(Request $request) {
        $website = $this->websiteDetector->detectOrFail($request);

//        $query = [
//            'filters' => [
//                SearchPages::FIELD_STATUS => [
//                    'operator' => 'expression',
//                    'value' => Page::STATUS_PUBLISHED,
//                ],
//
//                SearchPages::FIELD_TYPE => [
//                    'operator' => 'expression',
//                    'value' => Page::TYPE_POST,
//                ],
//
//                SearchPages::FIELD_WEBSITE_ID => [
//                    'operator' => 'expression',
//                    'value' => $website->id,
//                ],
//            ],
//
//            'orderBy' => [
//                SearchPages::FIELD_ID => 'desc',
//            ],
//        ];

        $numberOfPages = 0; // @todo

        $query['pagination'] = [
            'page' => $this->paginator->getCurrentPageNumber(),
            'perPage' => self::NUMBER_OF_ITEMS_PER_PAGE,
        ];

        $pages = new \Illuminate\Support\Collection(); // @todo

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
