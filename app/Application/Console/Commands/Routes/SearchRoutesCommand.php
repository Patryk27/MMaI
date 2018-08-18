<?php

namespace App\Application\Console\Commands\Routes;

use App\Routes\Exceptions\RouteException;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRoutesLikeUrlQuery;
use App\Routes\RoutesFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SearchRoutesCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'app:routes:search {query?}';

    /**
     * @var string
     */
    protected $description = 'Search for routes matching given query.';

    /**
     * @var RoutesFacade
     */
    private $routesFacade;

    /**
     * @param RoutesFacade $routesFacade
     */
    public function __construct(
        RoutesFacade $routesFacade
    ) {
        $this->routesFacade = $routesFacade;

        parent::__construct();
    }

    /**
     * @return void
     *
     * @throws RouteException
     */
    public function handle(): void
    {
        $routes = $this->findRoutes();

        $this->output->writeln(
            sprintf('Found <info>%d</info> route(s):', $routes->count())
        );

        $this->renderTable($routes);
    }

    /**
     * @return Collection|Route[]
     *
     * @throws RouteException
     */
    private function findRoutes(): Collection
    {
        $query = $this->input->getArgument('query') ?? '';

        if (!str_contains($query, '%')) {
            $query = '%' . $query . '%';
        }

        return $this->routesFacade
            ->queryMany(
                new GetRoutesLikeUrlQuery($query)
            )
            ->sortBy('url');
    }

    /**
     * @param Collection|Route[] $routes
     * @return void
     */
    private function renderTable(Collection $routes): void
    {
        $tableHeaders = [
            'id' => 'Id',
            'url' => 'URL',
            'points_at' => 'Points at',
            'created_at' => 'Created at',
        ];

        $tableRows = $routes->map(function (Route $route): array {
            return [
                'id' => $route->id,
                'url' => $route->url,
                'points_at' => sprintf('%s [id=%d]', $route->model_type, $route->model_id),
                'created_at' => $route->created_at->format('Y-m-d'),
            ];
        });

        $this->table($tableHeaders, $tableRows);
    }

}