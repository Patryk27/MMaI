<?php

use App\Application\Interfaces\Http\Controllers\Api\AttachmentsController;
use App\Application\Interfaces\Http\Controllers\Api\IndexController;
use App\Application\Interfaces\Http\Controllers\Api\PagesController;
use App\Application\Interfaces\Http\Controllers\Api\TagsController;

Route::domain('api.' . env('APP_DOMAIN'))->group(function () {
    // GET /
    Route::get('/', IndexController::class . '@index');

    // /attachments
    Route::prefix('attachments')->group(function () {
        // POST /attachments
        Route::post('/', AttachmentsController::class . '@store');

        // PUT /attachments/:attachment
        Route::put('/{attachment}', AttachmentsController::class . '@update');
    });

    // /pages
    Route::prefix('pages')->group(function () {
        // GET /pages/grid
        Route::get('grid', PagesController::class . '@gridSchema');

        // POST /pages/grid
        Route::post('grid', PagesController::class . '@gridQuery');

        // POST /pages
        Route::post('/', PagesController::class . '@store');

        // PUT /pages/:page
        Route::put('{page}', PagesController::class . '@update');
    });

    // /tags
    Route::prefix('tags')->group(function () {
        // GET /tags/grid
        Route::get('grid', TagsController::class . '@grid');

        // POST /tags/grid
        Route::get('grid', TagsController::class . '@gridSearch');

        // POST /tags
        Route::post('/', TagsController::class . '@store');

        // PUT /tags/:tag
        Route::put('{tag}', TagsController::class . '@update');

        // DELETE /tags/:tag
        Route::delete('{tag}', TagsController::class . '@destroy');
    });
});
