<?php

namespace App\App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{

    /**
     * @return void
     */
    public function map(): void
    {
        foreach (['backend', 'frontend'] as $namespace) {
            $this->loadRoutesFromNamespace($namespace);
        }
    }

    /**
     * @param string $namespace
     * @return void
     */
    private function loadRoutesFromNamespace(string $namespace): void
    {
        Route::middleware('web')
            ->group(
                base_path(
                    sprintf('routes/%s.php', $namespace)
                )
            );
    }

}
