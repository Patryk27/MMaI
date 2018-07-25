<?php

namespace App\App\Http\Controllers\Backend;

use App\App\Http\Controllers\Controller;
use App\Languages\LanguagesFacade;
use App\Languages\Queries\GetAllLanguagesQuery;
use App\Tags\TagsFacade;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;

class TagsController extends Controller
{

    /**
     * @var LanguagesFacade
     */
    private $languagesFacade;

    /**
     * @var TagsFacade
     */
    private $tagsFacade;

    /**
     * @param LanguagesFacade $languagesFacade
     * @param TagsFacade $tagsFacade
     */
    public function __construct(
        LanguagesFacade $languagesFacade,
        TagsFacade $tagsFacade
    ) {
        $this->languagesFacade = $languagesFacade;
        $this->tagsFacade = $tagsFacade;
    }

    /**
     * @return ViewContract
     */
    public function index(): ViewContract
    {
        $languages = $this->languagesFacade->queryMany(
            new GetAllLanguagesQuery()
        );

        $languages = $languages->sortBy('english_name');
        $languages = $languages->pluck('english_name', 'id');

        return view('backend.pages.tags.index', [
            'languages' => $languages,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request): array
    {
        throw new \App\Core\Exceptions\Exception('Not implemented.'); // @todo
//        $this->collectionRenderer->addColumns([
//            'id' => 'backend.pages.tags.search.columns.id',
//            'name' => 'backend.pages.tags.search.columns.name',
//            'page-count' => 'backend.pages.tags.search.columns.page-count',
//            'actions' => 'backend.pages.tags.search.columns.actions',
//        ]);
//
//        return $this->dataTableHandler->handle(
//            $this->tagsSearcher,
//            $this->collectionRenderer,
//            $request
//        );
    }

}