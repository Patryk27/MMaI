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
        Route::middleware('web')
            ->group(
                base_path('routes/backend.php')
            );

        Route::middleware('web')
            ->middleware('web:frontend')
            ->group(
                base_path('routes/frontend.php')
            );
    }

}
