<?php

namespace App\Application\Http\Controllers\Backend\Analytics;

use App\Analytics\AnalyticsFacade;
use App\Analytics\Queries\SearchRequestEventsQuery;
use App\Application\Http\Controllers\Controller;
use App\Core\DataTables\Handler as DataTablesHandler;
use App\Core\Table\Renderer as TableRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RequestsController extends Controller
{
    /** @var TableRenderer */
    private $tableRenderer;

    /** @var DataTablesHandler */
    private $dataTablesHandler;

    /** @var AnalyticsFacade */
    private $analyticsFacade;

    public function __construct(
        TableRenderer $tableRenderer,
        DataTablesHandler $dataTablesHandler,
        AnalyticsFacade $analyticsFacade
    ) {
        $this->tableRenderer = $tableRenderer;
        $this->dataTablesHandler = $dataTablesHandler;
        $this->analyticsFacade = $analyticsFacade;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return view('backend.views.analytics.requests');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request): array
    {
        $this->tableRenderer->addColumns([
            'id' => 'backend.components.table.id',
            'requestUrl' => 'backend.components.analytics.requests.table.request-url',
            'responseStatusCode' => 'backend.components.analytics.requests.table.response-status-code',
            'createdAt' => 'backend.components.table.created-at',
        ]);

        $this->dataTablesHandler->setRowsFetcher(function (array $query): Collection {
            return $this->tableRenderer->render(
                $this->analyticsFacade->queryMany(
                    new SearchRequestEventsQuery($query)
                )
            );
        });

        $this->dataTablesHandler->setRowsCounter(function (array $query): int {
            return $this->analyticsFacade->queryCount(
                new SearchRequestEventsQuery($query)
            );
        });

        return $this->dataTablesHandler->handle($request);
    }
}
