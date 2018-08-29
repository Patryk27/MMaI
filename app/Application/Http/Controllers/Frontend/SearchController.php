<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Frontend\SearchRequest;
use App\Pages\Exceptions\PageException;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPageVariantsQuery;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;

class SearchController extends Controller
{

    /**
     * @var ViewFactoryContract
     */
    private $viewFactory;

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @param ViewFactoryContract $viewFactory
     * @param PagesFacade $pagesFacade
     */
    public function __construct(
        ViewFactoryContract $viewFactory,
        PagesFacade $pagesFacade
    ) {
        $this->viewFactory = $viewFactory;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return view('frontend.pages.search.index');
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     *
     * @throws PageException
     */
    public function search(SearchRequest $request)
    {
        $query = [
            'search' => $request->get('search'),
            'filters' => $request->get('filters'),
        ];

        $pages = $this->pagesFacade->queryMany(
            new SearchPageVariantsQuery($query)
        );

        dd($pages);
    }

}