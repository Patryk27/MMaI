<?php

namespace App\Application\Console\Commands\Search;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Search\Queries\Search;
use App\Search\SearchFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

final class SearchCommand extends Command {

    public const NAME = 'app:search:query';

    /** @var string */
    protected $signature = 'app:search:query {query}';

    /** @var string */
    protected $description = 'Search for posts and pages matching given query.';

    /** @var SearchFacade */
    private $searchFacade;

    public function __construct(SearchFacade $searchFacade) {
        $this->searchFacade = $searchFacade;

        parent::__construct();
    }

    /**
     * @return void
     * @throws PageException
     */
    public function handle(): void {
        $pages = $this->searchFacade->search(
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
