<?php

namespace App\Application\Providers;

use App\Application\ViewComposers\Backend\Layout\NavigationComposer as BackendLayoutNavigationComposer;
use App\Application\ViewComposers\Frontend\Layout\NavigationComposer as FrontendLayoutNavigationComposer;
use App\Pages\Models\Page;
use App\Routes\Models\Route;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Schema;
use View;

final class ApplicationProvider extends ServiceProvider {

    /**
     * @return void
     */
    public function register(): void {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * @return void
     */
    public function boot(): void {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            Page::getMorphableType() => Page::class,
            Route::getMorphableType() => Route::class,
        ]);

        View::composers([
            BackendLayoutNavigationComposer::class => 'backend.layouts.authenticated.navigation',
            FrontendLayoutNavigationComposer::class => 'frontend.components.layout.navigation',
        ]);
    }

}
