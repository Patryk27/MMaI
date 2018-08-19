<?php

use App\Application\Http\Controllers\Frontend\DispatchController;
use App\Application\Http\Controllers\Frontend\HomeController;
use App\Application\Http\Controllers\Frontend\SearchController;

Route::domain('{subdomain}.' . env('APP_DOMAIN'))->group(function () {
    // /
    Route::get('/', HomeController::class . '@index');

    // --search
    Route::get('--search', SearchController::class . '@index');

    // catch-all for slugs
    Route::get('{url}', DispatchController::class . '@show')
        ->where('url', '(.+)');
});