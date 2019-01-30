<?php

namespace App\Application\Console\Commands\SearchEngine;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\SearchEngine\Queries\Search;
use App\SearchEngine\SearchEngineFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

final class SearchCommand extends Command {

    public const NAME = 'app:search-engine:search';

    /** @var string */
    protected $signature = 'app:search-engine:search {query}';

    /** @var string */
    protected $description = 'Search for posts and pages matching given query.';

    /** @var SearchEngineFacade */
    private $searchEngineFacade;

    public function __construct(SearchEngineFacade $searchEngineFacade) {
        $this->searchEngineFacade = $searchEngineFacade;

        parent::__construct();
    }

    /**
     * @return void
     * @throws PageException
     */
    public function handle(): void {
        $pages = $this->searchEngineFacade->search(
            new Search([
                'query' => $this->input->getArgument('query'),
            ])
        );

        if ($pages->isEmpty()) {
            $this->output->writeln(
                '<comment>No page matches given query.</comment>'
            );
        } else {
            $this->output->writeln(sprintf(
                'Found <info>%d</info> matching pages(s):', $pages->count()
            ));

            $this->renderTable($pages);
        }
    }

    /**
     * @param Collection $pages
     * @return void
     */
    private function renderTable(Collection $pages): void {
        $tableHeaders = [
            'id' => 'Id',
            'website' => 'Website',
            'route' => 'Route',
            'title' => 'Title',
            'created_at' => 'Created at',
        ];

        $tableRows = $pages->map(function (Page $page): array {
            return [
                'id' => $page->id,
                'website' => $page->website->slug,
                'route' => isset($page->route) ? $page->route->getAbsoluteUrl() : '',
                'title' => $page->title,
                'created_at' => $page->created_at->format('Y-m-d'),
            ];
        });

        $this->table($tableHeaders, $tableRows);
    }

}
