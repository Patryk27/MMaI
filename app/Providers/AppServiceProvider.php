<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Schema;
use View;

class AppServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function register(): void
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->singleton(\Joselfonseca\LaravelTactician\CommandBusInterface::class, \Joselfonseca\LaravelTactician\Bus::class);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            \App\Models\PageVariant::getMorphableType() => \App\Models\PageVariant::class,
            \App\Models\Route::getMorphableType() => \App\Models\Route::class,
        ]);

        View::composers([
            \App\ViewComposers\Backend\Layout\NavigationComposer::class => 'backend.layouts.authenticated.navigation',
            \App\ViewComposers\Backend\Pages\Pages\CreateEdit\FormComposer::class => 'backend.pages.pages.create-edit.form',

            \App\ViewComposers\Frontend\Layout\NavigationComposer::class => 'frontend.layout.navigation',
        ]);
    }

}
