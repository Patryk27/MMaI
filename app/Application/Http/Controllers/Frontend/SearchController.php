<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Languages\Exceptions\LanguageException;
use App\Languages\LanguagesFacade;
use App\Languages\Queries\GetAllLanguagesQuery;
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
     * @param ViewFactoryContract $viewFactory
     * @param LanguagesFacade $languagesFacade
     */
    public function __construct(
        ViewFactoryContract $viewFactory,
        LanguagesFacade $languagesFacade
    ) {
        $this->viewFactory = $viewFactory;
        $this->languagesFacade = $languagesFacade;
    }

    /**
     * @return mixed
     *
     * @throws LanguageException
     */
    public function index()
    {
        return view('frontend.pages.search', [
            'languages' => $this->languagesFacade->queryMany(
                new GetAllLanguagesQuery()
            )
        ]);
    }

}