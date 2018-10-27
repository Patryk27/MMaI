<?php

namespace App\Application\Http\Controllers\Backend;

use App\Analytics\AnalyticsFacade;
use App\Analytics\Models\Event;
use App\Analytics\Queries\SearchRequestEventsQuery;
use App\Application\Http\Controllers\Controller;
use App\Core\DataTables\Handler as DataTablesHandler;
use App\Core\Table\Renderer as TableRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AnalyticsController extends Controller
{

    /**
     * @var TableRenderer
     */
    private $tableRenderer;

    /**
     * @var DataTablesHandler
     */
    private $dataTablesHandler;

    /**
     * @var AnalyticsFacade
     */
    private $analyticsFacade;

    /**
     * @param TableRenderer $tableRenderer
     * @param DataTablesHandler $dataTablesHandler
     * @param AnalyticsFacade $analyticsFacade
     */
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
        return view('backend.pages.analytics.index');
    }

    /**
     * @return mixed
     */
    public function requests()
    {
        return view('backend.pages.analytics.requests', [
            'types' => [
                Event::TYPE_REQUEST_SERVED => Event::TYPE_REQUEST_SERVED,
                Event::TYPE_REQUEST_FAILED => Event::TYPE_REQUEST_FAILED,
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function searchRequests(Request $request): array
    {
        $this->tableRenderer->addColumns([
            'id' => 'backend.components.table.id',
            'type' => 'backend.components.analytics.table.type',
            'url' => 'backend.components.analytics.requests.table.url',
            'created-at' => 'backend.components.table.created-at',
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
