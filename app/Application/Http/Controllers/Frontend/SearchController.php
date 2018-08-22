<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Frontend\SearchRequest;
use App\Languages\Exceptions\LanguageException;
use App\Languages\LanguagesFacade;
use App\Languages\Queries\GetAllLanguagesQuery;
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
     * @var LanguagesFacade
     */
    private $languagesFacade;

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @param ViewFactoryContract $viewFactory
     * @param LanguagesFacade $languagesFacade
     * @param PagesFacade $pagesFacade
     */
    public function __construct(
        ViewFactoryContract $viewFactory,
        LanguagesFacade $languagesFacade,
        PagesFacade $pagesFacade
    ) {
        $this->viewFactory = $viewFactory;
        $this->languagesFacade = $languagesFacade;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @return mixed
     *
     * @throws LanguageException
     */
    public function index()
    {
        return view('frontend.pages.search.index', [
            'languages' => $this->languagesFacade->queryMany(
                new GetAllLanguagesQuery()
            )
        ]);
    }

    /**
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