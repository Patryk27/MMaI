<?php

namespace App\Application\Providers;

use App\Languages\LanguagesFacade;
use App\Menus\MenusFacade;
use App\Pages\PagesFacade;
use App\Routes\RoutesFacade;
use App\SearchEngine\SearchEngineFacade;
use App\Tags\TagsFacade;
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
            /**
             * Registering facade as singleton and executing its constructor is
             * vital here, since it allows the facade to set up all its event
             * listeners.
             *
             * If we were to skip this step, the @see SearchEngineFacade for
             * instance would have no place to register the "page created" event
             * listener and thus would never know when to update the
             * Elasticsearch's index.
             *
             * Luckily, we do the registering thing.
             */

            $this->app->singleton($facadeClass);
            $this->app->make($facadeClass);
        }
    }

}