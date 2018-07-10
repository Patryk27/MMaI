<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\Exception as AppException;
use App\Http\Controllers\Controller;
use App\Repositories\LanguagesRepository;
use App\Services\Tags\Searcher as TagsSearcher;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;

class TagsController extends Controller
{

    /**
     * @var LanguagesRepository
     */
    private $languagesRepository;

    /**
     * @var TagsSearcher
     */
    private $tagsSearcher;

    /**
     * @param LanguagesRepository $languagesRepository
     * @param TagsSearcher $tagsSearcher
     */
    public function __construct(
        LanguagesRepository $languagesRepository,
        TagsSearcher $tagsSearcher
    ) {
        $this->languagesRepository = $languagesRepository;
        $this->tagsSearcher = $tagsSearcher;
    }

    /**
     * @return ViewContract
     */
    public function index()
    {
        $languages = $this->languagesRepository->getAll();
        $languages = $languages->sortBy('english_name');
        $languages = $languages->pluck('english_name', 'id');

        return view('backend.pages.tags.index', [
            'languages' => $languages,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     *
     * @throws AppException
     */
    public function search(Request $request)
    {
        $this->tagsSearcher->filter(
            $request->get('filters', [])
        );

        $this->tagsSearcher->orderBy(TagsSearcher::FIELD_NAME, true);

        return [
            'items' => $this->tagsSearcher->get(),
        ];
    }

}