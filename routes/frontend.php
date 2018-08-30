<?php

use App\Application\Http\Controllers\Frontend\DispatchController;
use App\Application\Http\Controllers\Frontend\HomeController;
use App\Application\Http\Controllers\Frontend\SearchController;

Route::domain('{subdomain}.' . env('APP_DOMAIN'))->group(function () {
    // /
    Route::get('/', HomeController::class . '@index');

    // !search
    Route::get('!search', SearchController::class . '@search');

    // catch-all for slugs
    Route::get('{url}', DispatchController::class . '@show')
        ->where('url', '(.+)');
});

Route::redirect('/', sprintf('%s://%s.%s', env('APP_PROTOCOL'), 'en', env('APP_DOMAIN')));