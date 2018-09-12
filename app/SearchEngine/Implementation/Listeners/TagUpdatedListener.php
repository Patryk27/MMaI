<?php

namespace App\SearchEngine\Implementation\Listeners;

use App\Pages\Exceptions\PageException;
use App\Pages\PagesFacade;
use App\Pages\Queries\GetPageVariantsByTagIdQuery;
use App\SearchEngine\SearchEngineFacade;
use App\Tags\Events\TagUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

final class TagUpdatedListener implements ShouldQueue
{

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @var SearchEngineFacade
     */
    private $searchEngineFacade;

    /**
     * @param PagesFacade $pagesFacade
     * @param SearchEngineFacade $searchEngineFacade
     */
    public function __construct(
        PagesFacade $pagesFacade,
        SearchEngineFacade $searchEngineFacade
    ) {
        $this->pagesFacade = $pagesFacade;
        $this->searchEngineFacade = $searchEngineFacade;
    }

    /**
     * @param TagUpdated $event
     * @return void
     *
     * @throws PageException
     */
    public function handle(TagUpdated $event): void
    {
        $pageVariants = $this->pagesFacade->queryMany(
            new GetPageVariantsByTagIdQuery(
                $event->getTag()->id
            )
        );

        foreach ($pageVariants as $pageVariant) {
            $this->searchEngineFacade->index($pageVariant->page);
        }
    }

}
