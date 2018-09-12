<?php

namespace App\Application\Providers;

use App\Languages\LanguagesFacade;
use App\Menus\MenusFacade;
use App\Pages\PagesFacade;
use App\Routes\RoutesFacade;
use App\SearchEngine\SearchEngineFacade;
use App\Tags\TagsFacade;
use Event;
use Illuminate\Support\ServiceProvider;

final class FacadeServiceProvider extends ServiceProvider
{

    private const FACADES = [
        LanguagesFacade::class,
        MenusFacade::class,
        PagesFacade::class,
        RoutesFacade::class,
        SearchEngineFacade::class,
        TagsFacade::class,
    ];

    /**
     * @return void
     */
    public function boot(): void
    {
        foreach (self::FACADES as $facadeClass) {
            $this->bootFacade($facadeClass);
        }
    }

    /**
     * @param string $facadeClass
     * @return void
     */
    private function bootFacade(string $facadeClass): void
    {
        $this->app->singleton($facadeClass);
        $facade = $this->app->make($facadeClass);

        if (method_exists($facade, 'getListeners')) {
            foreach ($facade::getListeners() as $eventName => $listenerClass) {
                Event::listen($eventName, $listenerClass);
            }
        }
    }

}
