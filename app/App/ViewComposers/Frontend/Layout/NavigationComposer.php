<?php

namespace App\App\ViewComposers\Frontend\Layout;

use App\Core\Exceptions\Exception;
use App\Core\Services\Language\Detector as LanguageDetector;
use App\Languages\Models\Language;
use App\Menus\Exceptions\MenuException;
use App\Menus\MenusFacade;
use App\Menus\Models\MenuItem;
use App\Menus\Queries\GetMenuItemsByLanguageIdQuery;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class NavigationComposer
{

    /**
     * @var LanguageDetector
     */
    private $languageDetector;

    /**
     * @var MenusFacade
     */
    private $menusFacade;

    /**
     * @param LanguageDetector $languageDetector
     * @param MenusFacade $menusFacade
     */
    public function __construct(
        LanguageDetector $languageDetector,
        MenusFacade $menusFacade
    ) {
        $this->languageDetector = $languageDetector;
        $this->menusFacade = $menusFacade;
    }

    /**
     * @param View $view
     * @return void
     *
     * @throws Exception
     */
    public function compose(View $view): void
    {
        $currentLanguage = $this->languageDetector->getLanguageOrFail();

        $view->with([
            'homeUrl' => $this->getHomeUrl($currentLanguage),
            'menuItems' => $this->getMenuItems($currentLanguage),
        ]);
    }

    /**
     * @param Language $currentLanguage
     * @return string
     */
    private function getHomeUrl(Language $currentLanguage): string
    {
        return '/' . $currentLanguage->slug;
    }

    /**
     * @param Language $currentLanguage
     * @return Collection|MenuItem[]
     *
     * @throws MenuException
     */
    private function getMenuItems(Language $currentLanguage): Collection
    {
        return $this->menusFacade->queryMany(
            new GetMenuItemsByLanguageIdQuery($currentLanguage->id)
        );
    }

}