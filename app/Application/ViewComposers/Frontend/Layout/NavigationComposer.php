<?php

namespace App\Application\ViewComposers\Frontend\Layout;

use App\Core\Exceptions\Exception;
use App\Core\Websites\WebsiteDetector;
use App\Menus\Exceptions\MenuException;
use App\Menus\MenusFacade;
use App\Menus\Models\MenuItem;
use App\Menus\Queries\GetMenuItemsByWebsiteId;
use App\Websites\Models\Website;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

final class NavigationComposer {

    /** @var Request */
    private $request;

    /** @var WebsiteDetector */
    private $websiteDetector;

    /** @var MenusFacade */
    private $menusFacade;

    public function __construct(
        Request $request,
        WebsiteDetector $websiteDetector,
        MenusFacade $menusFacade
    ) {
        $this->request = $request;
        $this->websiteDetector = $websiteDetector;
        $this->menusFacade = $menusFacade;
    }

    /**
     * @param View $view
     * @return void
     * @throws Exception
     */
    public function compose(View $view): void {
        $website = $this->websiteDetector->detectOrFail($this->request);

        $view->with([
            'website' => $website,
            'menuItems' => $this->getMenuItems($website),
        ]);
    }

    /**
     * @param Website $website
     * @return Collection|MenuItem[]
     * @throws MenuException
     */
    private function getMenuItems(Website $website): Collection {
        return $this->menusFacade->queryMany(
            new GetMenuItemsByWebsiteId($website->id)
        );
    }

}
