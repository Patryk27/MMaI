<?php

namespace App\Application\ViewComposers\Frontend\Layout;

use App\Core\Exceptions\Exception;
use App\Core\Language\Detector as LanguageDetector;
use App\Languages\Models\Language;
use App\Menus\Exceptions\MenuException;
use App\Menus\MenusFacade;
use App\Menus\Models\MenuItem;
use App\Menus\Queries\GetMenuItemsByLanguageIdQuery;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class NavigationComposer
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var LanguageDetector
     */
    private $languageDetector;

    /**
     * @var MenusFacade
     */
    private $menusFacade;

    /**
     * @param Request $request
     * @param LanguageDetector $languageDetector
     * @param MenusFacade $menusFacade
     */
    public function __construct(
        Request $request,
        LanguageDetector $languageDetector,
        MenusFacade $menusFacade
    ) {
        $this->request = $request;
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
        $currentLanguage = $this->languageDetector->detectOrFail($this->request);

        $view->with([
            'menuItems' => $this->getMenuItems($currentLanguage),
        ]);
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
