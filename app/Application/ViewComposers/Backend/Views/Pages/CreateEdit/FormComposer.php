<?php

namespace App\Application\ViewComposers\Backend\Views\Pages\CreateEdit;

use App\Core\Exceptions\Exception;
use App\Tags\Queries\GetAllTagsQuery;
use App\Tags\TagsFacade;
use App\Websites\Queries\GetAllWebsitesQuery;
use App\Websites\WebsitesFacade;
use Illuminate\Contracts\View\View;

class FormComposer
{
    /** @var TagsFacade */
    private $tagsFacade;

    /** @var WebsitesFacade */
    private $websitesFacade;

    public function __construct(
        TagsFacade $tagsFacade,
        WebsitesFacade $websitesFacade
    ) {
        $this->tagsFacade = $tagsFacade;
        $this->websitesFacade = $websitesFacade;
    }

    /**
     * @param View $view
     * @return void
     * @throws Exception
     */
    public function compose(View $view): void
    {
        $view->with([
            'websites' => $this->websitesFacade->queryMany(
                new GetAllWebsitesQuery()
            ),
        ]);
    }
}
