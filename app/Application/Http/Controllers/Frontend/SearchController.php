<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Frontend\SearchRequest;
use App\Core\Exceptions\Exception as CoreException;
use App\Core\Language\Detector as LanguageDetector;
use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Pages\PagesFacade;
use App\Pages\ValueObjects\RenderedPageVariant;
use App\SearchEngine\Queries\SearchQuery;
use App\SearchEngine\SearchEngineFacade;
use Illuminate\Support\Collection;

class SearchController extends Controller
{

    /**
     * @var LanguageDetector
     */
    private $languageDetector;

    /**
     * @var SearchEngineFacade
     */
    private $searchEngineFacade;

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @param LanguageDetector $languageDetector
     * @param SearchEngineFacade $searchEngineFacade
     * @param PagesFacade $pagesFacade
     */
    public function __construct(
        LanguageDetector $languageDetector,
        SearchEngineFacade $searchEngineFacade,
        PagesFacade $pagesFacade
    ) {
        $this->languageDetector = $languageDetector;
        $this->searchEngineFacade = $searchEngineFacade;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return view('frontend.views.search.index');
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     *
     * @throws PageException
     * @throws CoreException
     */
    public function search(SearchRequest $request)
    {
        $language = $this->languageDetector->getLanguageOrFail();

        /**
         * @var Collection|PageVariant[] $posts
         */
        $posts = $this->searchEngineFacade->search(
            new SearchQuery([
                'query' => $request->get('query'),
                'pageType' => Page::TYPE_BLOG,
                'language' => $language,
            ])
        );

        /**
         * @var Collection|RenderedPageVariant[] $posts
         */
        $posts = $posts->map([$this->pagesFacade, 'render']);

        return view('frontend.views.search', [
            'query' => $request->get('query'),
            'posts' => $posts,
        ]);
    }

}
