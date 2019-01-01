<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Tags\CreateTagRequest;
use App\Application\Http\Requests\Backend\Tags\UpdateTagRequest;
use App\Core\DataTables\Handler as DataTablesHandler;
use App\Core\Table\Renderer as TableRenderer;
use App\Tags\Exceptions\TagException;
use App\Tags\Models\Tag;
use App\Tags\Queries\SearchTagsQuery;
use App\Tags\TagsFacade;
use App\Websites\Exceptions\WebsiteException;
use App\Websites\Queries\GetAllWebsitesQuery;
use App\Websites\WebsitesFacade;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagsController extends Controller
{
    /** @var TableRenderer */
    private $tableRenderer;

    /** @var DataTablesHandler */
    private $dataTablesHandler;

    /** @var TagsFacade */
    private $tagsFacade;

    /** @var WebsitesFacade */
    private $websitesFacade;

    public function __construct(
        TableRenderer $tableRenderer,
        DataTablesHandler $dataTablesHandler,
        TagsFacade $tagsFacade,
        WebsitesFacade $websitesFacade
    ) {
        $this->tableRenderer = $tableRenderer;
        $this->dataTablesHandler = $dataTablesHandler;
        $this->tagsFacade = $tagsFacade;
        $this->websitesFacade = $websitesFacade;
    }

    /**
     * @return View
     * @throws WebsiteException
     */
    public function index(): View
    {
        $websites = $this->websitesFacade->queryMany(
            new GetAllWebsitesQuery()
        );

        return view('backend.views.tags.index', [
            'websites' => $websites->sortBy('name')->pluck('name', 'id'),
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
