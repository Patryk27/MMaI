<?php

namespace App\Application\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Schema;
use View;

final class AppServiceProvider extends ServiceProvider
{

    private const BINDS = [
        // Languages
        \App\Languages\Implementation\Repositories\LanguagesRepositoryInterface::class => \App\Languages\Implementation\Repositories\LanguagesEloquentRepository::class,

        // Menus
        \App\Menus\Implementation\Repositories\MenuItemsRepositoryInterface::class => \App\Menus\Implementation\Repositories\EloquentMenuItemsRepository::class,

        // Pages
        \App\Pages\Implementation\Repositories\PagesRepositoryInterface::class => \App\Pages\Implementation\Repositories\EloquentPagesRepository::class,
        \App\Pages\Implementation\Services\PageVariants\PageVariantsSearcherInterface::class => \App\Pages\Implementation\Services\PageVariants\Searcher\EloquentPageVariantsSearcher::class,

        // Routes
        \App\Routes\Implementation\Repositories\RoutesRepositoryInterface::class => \App\Routes\Implementation\Repositories\EloquentRoutesRepository::class,

        // Tags
        \App\Tags\Implementation\Repositories\TagsRepositoryInterface::class => \App\Tags\Implementation\Repositories\EloquentTagsRepository::class,
        \App\Tags\Implementation\Services\TagsSearcherInterface::class => \App\Tags\Implementation\Services\Searcher\EloquentTagsSearcher::class,
    ];

    /**
     * @return void
     */
    public function register(): void
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        foreach (self::BINDS as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            \App\Pages\Models\PageVariant::getMorphableType() => \App\Pages\Models\PageVariant::class,
            \App\Routes\Models\Route::getMorphableType() => \App\Routes\Models\Route::class,
        ]);

        View::composers([
            // Backend-related composers
            \App\Application\ViewComposers\Backend\Layout\NavigationComposer::class => 'backend.layouts.authenticated.navigation',
            \App\Application\ViewComposers\Backend\Pages\Pages\CreateEdit\FormComposer::class => 'backend.pages.pages.create-edit.form',

            // Frontend-related composers
            \App\Application\ViewComposers\Frontend\Layout\NavigationComposer::class => 'frontend.layout.navigation',
        ]);
    }

}
