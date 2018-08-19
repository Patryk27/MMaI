<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Tags\TagCreateRequest;
use App\Application\Http\Requests\Backend\Tags\TagUpdateRequest;
use App\Core\Services\Collection\Renderer as CollectionRenderer;
use App\Core\Services\DataTables\Handler as DataTablesHandler;
use App\Languages\Exceptions\LanguageException;
use App\Languages\LanguagesFacade;
use App\Languages\Queries\GetAllLanguagesQuery;
use App\Tags\Exceptions\TagException;
use App\Tags\Models\Tag;
use App\Tags\Queries\SearchTagsQuery;
use App\Tags\TagsFacade;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagsController extends Controller
{

    /**
     * @var CollectionRenderer
     */
    private $collectionRenderer;

    /**
     * @var DataTablesHandler
     */
    private $dataTablesHandler;

    /**
     * @var LanguagesFacade
     */
    private $languagesFacade;

    /**
     * @var TagsFacade
     */
    private $tagsFacade;

    /**
     * @param CollectionRenderer $collectionRenderer
     * @param DataTablesHandler $dataTablesHandler
     * @param LanguagesFacade $languagesFacade
     * @param TagsFacade $tagsFacade
     */
    public function __construct(
        CollectionRenderer $collectionRenderer,
        DataTablesHandler $dataTablesHandler,
        LanguagesFacade $languagesFacade,
        TagsFacade $tagsFacade
    ) {
        $this->collectionRenderer = $collectionRenderer;
        $this->dataTablesHandler = $dataTablesHandler;
        $this->languagesFacade = $languagesFacade;
        $this->tagsFacade = $tagsFacade;
    }

    /**
     * @return ViewContract
     *
     * @throws LanguageException
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
        $this->collectionRenderer->addColumns([
            'id' => 'backend.pages.tags.search.columns.id',
            'name' => 'backend.pages.tags.search.columns.name',
            'page-count' => 'backend.pages.tags.search.columns.page-count',
            'created-at' => 'backend.pages.tags.search.columns.created-at',
            'actions' => 'backend.pages.tags.search.columns.actions',
        ]);

        $this->dataTablesHandler->setQueryHandler(function (array $query): Collection {
            return $this->collectionRenderer->render(
                $this->tagsFacade->queryMany(
                    new SearchTagsQuery($query)
                )
            );
        });

        $this->dataTablesHandler->setQueryCountHandler(function (array $query): int {
            return $this->tagsFacade->queryCount(
                new SearchTagsQuery($query)
            );
        });

        return $this->dataTablesHandler->handle($request);
    }

    /**
     * @param TagCreateRequest $request
     * @return void
     *
     * @throws TagException
     */
    public function store(TagCreateRequest $request)
    {
        $this->tagsFacade->create(
            $request->all()
        );
    }

    /**
     * @param TagUpdateRequest $request
     * @param Tag $tag
     * @return void
     *
     * @throws TagException
     */
    public function update(TagUpdateRequest $request, Tag $tag)
    {
        $this->tagsFacade->update(
            $tag,
            $request->all()
        );
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function destroy(Tag $tag)
    {
        $this->tagsFacade->delete($tag);
    }

}