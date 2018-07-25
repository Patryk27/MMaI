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
        foreach (['backend', 'frontend'] as $part) {
            $this->loadPart($part);
        }
    }

    /**
     * @param string $part
     * @return void
     */
    private function loadPart(string $part): void
    {
        Route::middleware('web')
            ->group(
                base_path(
                    sprintf('routes/%s.php', $part)
                )
            );
    }

}
