<?php

namespace App\App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Schema;
use View;

class AppServiceProvider extends ServiceProvider
{

    private const BINDS = [
        \App\Routes\Internal\Repositories\RoutesRepositoryInterface::class => \App\Routes\Internal\Repositories\RoutesEloquentRepository::class,
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
            \App\App\ViewComposers\Backend\Layout\NavigationComposer::class => 'backend.layouts.authenticated.navigation',
            \App\App\ViewComposers\Backend\Pages\Pages\CreateEdit\FormComposer::class => 'backend.pages.pages.create-edit.form',

            // Frontend-related composers
            \App\App\ViewComposers\Frontend\Layout\NavigationComposer::class => 'frontend.layout.navigation',
        ]);
    }

}
