<?php

namespace App\ViewComposers\Frontend\Layout;

use App\Exceptions\Exception;
use App\Models\Language;
use App\Models\MenuItem;
use App\Repositories\MenuItemsRepository;
use App\Services\Core\Language\Detector as LanguageDetector;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class NavigationComposer
{

    /**
     * @var LanguageDetector
     */
    private $languageDetector;

    /**
     * @var MenuItemsRepository
     */
    private $menuItemsRepository;

    /**
     * @var Language
     */
    private $currentLanguage;

    /**
     * @param LanguageDetector $languageDetector
     * @param MenuItemsRepository $menuItemsRepository
     */
    public function __construct(
        LanguageDetector $languageDetector,
        MenuItemsRepository $menuItemsRepository
    ) {
        $this->languageDetector = $languageDetector;
        $this->menuItemsRepository = $menuItemsRepository;
    }

    /**
     * @param View $view
     * @return void
     *
     * @throws Exception
     */
    public function compose(View $view): void
    {
        $this->currentLanguage = $this->languageDetector->getLanguageOrFail();

        $view->with([
            'homeUrl' => $this->getHomeUrl(),
            'menuItems' => $this->getMenuItems(),
        ]);
    }

    /**
     * @return string
     */
    private function getHomeUrl(): string
    {
        return '/' . $this->currentLanguage->slug;
    }

    /**
     * @return Collection|MenuItem[]
     */
    private function getMenuItems(): Collection
    {
        return $this->menuItemsRepository->getByLanguageId($this->currentLanguage->id);
    }

}