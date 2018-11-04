<?php

namespace App\Application\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Schema;
use View;

final class AppServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function register(): void
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            \App\Pages\Models\Page::getMorphableType() => \App\Pages\Models\Page::class,
            \App\Pages\Models\PageVariant::getMorphableType() => \App\Pages\Models\PageVariant::class,
            \App\Routes\Models\Route::getMorphableType() => \App\Routes\Models\Route::class,
        ]);

        View::composers([
            // Backend-related composers
            \App\Application\ViewComposers\Backend\Layout\NavigationComposer::class => 'backend.layouts.authenticated.navigation',
            \App\Application\ViewComposers\Backend\Pages\Pages\CreateEdit\FormComposer::class => 'backend.views.pages.create-edit.form',

            // Frontend-related composers
            \App\Application\ViewComposers\Frontend\Layout\NavigationComposer::class => 'frontend.layout.navigation',
        ]);
    }

}
