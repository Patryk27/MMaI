<?php

namespace App\Application\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

final class RoutesProvider extends BaseRouteServiceProvider
{
    /**
     * @return void
     */
    public function map(): void
    {
        Route::middleware(['web', 'web:api'])->group(
            base_path('routes/api.php')
        );

        Route::middleware(['web', 'web:backend'])->group(
            base_path('routes/backend.php')
        );

        Route::middleware(['web', 'web:frontend'])->group(
            base_path('routes/frontend.php')
        );
    }
}
