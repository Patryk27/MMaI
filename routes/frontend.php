<?php

use App\Application\Http\Controllers\Frontend\AttachmentsController;
use App\Application\Http\Controllers\Frontend\DispatchController;
use App\Application\Http\Controllers\Frontend\HomeController;
use App\Application\Http\Controllers\Frontend\SearchController;

// Set-up per-language subdomain routing
Route::domain('{subdomain}.' . env('APP_DOMAIN'))->group(function () {
    // /
    Route::get('/', HomeController::class . '@index');

    // !search
    Route::get('!search', SearchController::class . '@search');

    // !attachments/{path}
    Route::get('!attachments/{path}', AttachmentsController::class . '@attachment');

    // catch-all for slugs
    Route::get('{url}', DispatchController::class . '@show')
        ->where('url', '(.+)');
});

Route::domain(env('APP_DOMAIN'))->group(function() {
    // /attachments
    Route::group(['prefix' => 'attachments'], function () {
        // /attachments/{path}
        Route::get('{path}', AttachmentsController::class . '@attachment')
            ->name('frontend.attachments.download');
    });

    // Redirect all requests at the `/` page onto the English version
    Route::redirect('/', sprintf('%s://%s.%s', env('APP_PROTOCOL'), 'en', env('APP_DOMAIN')));
});
