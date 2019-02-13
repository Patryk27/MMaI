<?php

namespace App\Application\Console\Commands\Search;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPages;
use App\Search\SearchFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

final class ReindexAllCommand extends Command {

    /** @var string */
    protected $signature = 'app:search:reindex';

    /** @var string */
    protected $description = 'Reindex all the pages.';

    /** @var PagesFacade */
    private $pagesFacade;

    /** @var SearchFacade */
    private $searchFacade;

    public function __construct(
        PagesFacade $pagesFacade,
        SearchFacade $searchFacade
    ) {
        $this->pagesFacade = $pagesFacade;
        $this->searchFacade = $searchFacade;

        parent::__construct();
    }

    /**
     * @return void
     * @throws PageException
     */
    public function handle(): void {
        $this->reindexPages(
            $this->getPages()
        );
    }

    /**
     * @return Collection|Page[]
     * @throws PageException
     */
    private function getPages(): Collection {
        return $this->pagesFacade->queryMany(
            new SearchPages([])
        );
    }

    /**
     * @param Collection $pages
     * @return void
     */
    private function reindexPages(Collection $pages): void {
        $this->output->writeln(sprintf(
            'About to reindex <info>%d</info> pages...', $pages->count()
        ));

        // Prepare progress bar
        $progressBar = $this->output->createProgressBar(
            $pages->count()
        );

        $progressBar->display();

        // Begin to re-index all the pages
        foreach ($pages as $page) {
            $this->searchFacade->index($page);
            $progressBar->advance();
        }

        // Finish progress bar
        $progressBar->finish();
        $this->output->writeln('');
    }

}
