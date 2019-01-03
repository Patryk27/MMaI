<?php

use App\Application\Http\Controllers\Frontend\AttachmentsController;
use App\Application\Http\Controllers\Frontend\DispatchController;
use App\Application\Http\Controllers\Frontend\HomeController;
use App\Application\Http\Controllers\Frontend\SearchController;

// Set-up per-website routing
Route::domain('{subdomain}.' . env('APP_DOMAIN'))->group(function () {
    // GET /
    Route::get('/', HomeController::class . '@index');

    // GET !search
    Route::get('!search', SearchController::class . '@search');

    // GET !attachments/{path}
    Route::get('!attachments/{path}', AttachmentsController::class . '@attachment');

    // GET (catch-all for slugs)
    Route::get('{url}', DispatchController::class . '@dispatch')
        ->where('url', '(.+)');
});

Route::domain(env('APP_DOMAIN'))->group(function () {
    // GET /attachments/:attachment
    Route::get('attachments/{path}', AttachmentsController::class . '@attachment')
        ->name('frontend.attachments.download');

    // Redirect all requests at the `/` page onto the English version
    // @todo this behavior should be configurable
    Route::redirect('/', sprintf('%s://%s.%s', env('APP_PROTOCOL'), 'en', env('APP_DOMAIN')));
});
