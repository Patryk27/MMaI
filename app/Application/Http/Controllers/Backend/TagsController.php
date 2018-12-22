<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Tags\CreateTagRequest;
use App\Application\Http\Requests\Backend\Tags\UpdateTagRequest;
use App\Core\DataTables\Handler as DataTablesHandler;
use App\Core\Table\Renderer as TableRenderer;
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
    /** @var TableRenderer */
    private $tableRenderer;

    /** @var DataTablesHandler */
    private $dataTablesHandler;

    /** @var LanguagesFacade */
    private $languagesFacade;

    /** @var TagsFacade */
    private $tagsFacade;

    public function __construct(
        TableRenderer $tableRenderer,
        DataTablesHandler $dataTablesHandler,
        LanguagesFacade $languagesFacade,
        TagsFacade $tagsFacade
    ) {
        $this->tableRenderer = $tableRenderer;
        $this->dataTablesHandler = $dataTablesHandler;
        $this->languagesFacade = $languagesFacade;
        $this->tagsFacade = $tagsFacade;
    }

    /**
     * @return ViewContract
     * @throws LanguageException
     */
    public function index(): ViewContract
    {
        $languages = $this->languagesFacade->queryMany(
            new GetAllLanguagesQuery()
        );

        $languages = $languages->sortBy('english_name');
        $languages = $languages->pluck('english_name', 'id');

        return view('backend.views.tags.index', [
            'languages' => $languages,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request): array
    {
        $this->tableRenderer->addColumns([
            'id' => 'backend.components.table.id',
            'name' => 'backend.components.tags.table.name',
            'assignedPagesCount' => 'backend.components.tags.table.assigned-pages-count',
            'createdAt' => 'backend.components.table.created-at',
            'actions' => 'backend.components.tags.table.actions',
        ]);

        $this->dataTablesHandler->setRowsFetcher(function (array $query): Collection {
            return $this->tableRenderer->render(
                $this->tagsFacade->queryMany(
                    new SearchTagsQuery($query)
                )
            );
        });

        $this->dataTablesHandler->setRowsCounter(function (array $query): int {
            return $this->tagsFacade->queryCount(
                new SearchTagsQuery($query)
            );
        });

        return $this->dataTablesHandler->handle($request);
    }

    /**
     * @param CreateTagRequest $request
     * @return void
     * @throws TagException
     */
    public function store(CreateTagRequest $request)
    {
        $this->tagsFacade->create(
            $request->all()
        );
    }

    /**
     * @param UpdateTagRequest $request
     * @param Tag $tag
     * @return void
     * @throws TagException
     */
    public function update(UpdateTagRequest $request, Tag $tag)
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
