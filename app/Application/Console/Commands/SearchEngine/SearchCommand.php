<?php

namespace App\Application\Console\Commands\SearchEngine;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\PageVariant;
use App\SearchEngine\Queries\SearchQuery;
use App\SearchEngine\SearchEngineFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

final class SearchCommand extends Command
{

    public const NAME = 'app:search-engine:search';

    /**
     * @var string
     */
    protected $signature = 'app:search-engine:search {query}';

    /**
     * @var string
     */
    protected $description = 'Search for posts and pages matching given query.';

    /**
     * @var SearchEngineFacade
     */
    private $searchEngineFacade;

    /**
     * @param SearchEngineFacade $searchEngineFacade
     */
    public function __construct(
        SearchEngineFacade $searchEngineFacade
    ) {
        $this->searchEngineFacade = $searchEngineFacade;

        parent::__construct();
    }

    /**
     * @return void
     *
     * @throws PageException
     */
    public function handle(): void
    {
        $pageVariants = $this->searchEngineFacade->search(
            new SearchQuery([
                'query' => $this->input->getArgument('query'),
            ])
        );

        if ($pageVariants->isEmpty()) {
            $this->output->writeln(
                '<comment>No page variant matches given query.</comment>'
            );
        } else {
            $this->output->writeln(
                sprintf('Found <info>%d</info> matching page variants(s):', $pageVariants->count())
            );

            $this->renderTable($pageVariants);
        }
    }

    /**
     * @param Collection $pageVariants
     * @return void
     */
    private function renderTable(Collection $pageVariants): void
    {
        $tableHeaders = [
            'id' => 'Id',
            'page_id' => 'Page id',
            'route' => 'Route',
            'title' => 'Title',
            'language' => 'Language',
            'created_at' => 'Created at',
        ];

        $tableRows = $pageVariants->map(function (PageVariant $pageVariant): array {
            return [
                'id' => $pageVariant->id,
                'page_id' => $pageVariant->page_id,
                'route' => isset($pageVariant->route) ? $pageVariant->route->getEntireUrl() : '',
                'title' => $pageVariant->title,
                'language' => $pageVariant->language->english_name,
                'created_at' => $pageVariant->created_at->format('Y-m-d'),
            ];
        });

        $this->table($tableHeaders, $tableRows);
    }

}
