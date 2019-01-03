<?php

use App\Application\Http\Controllers\Api\AttachmentsController;
use App\Application\Http\Controllers\Api\PagesController;
use App\Application\Http\Controllers\Api\TagsController;

Route::prefix('api')->group(function () {
    // /api/attachments
    Route::prefix('attachments')->group(function () {
        // POST /api/attachments
        Route::post('attachments', AttachmentsController::class . '@store')
            ->name('backend.attachments.store');
    });

    // /api/pages
    Route::prefix('pages')->group(function () {
        // GET /api/pages
        Route::get('/', PagesController::class . '@index');

        // POST /api/pages
        Route::post('/', PagesController::class . '@store');

        // PUT /api/pages/:page
        Route::put('{page}', PagesController::class . '@update');
    });

    // /api/tags
    Route::prefix('tags')->group(function () {
        // GET /api/tags
        Route::get('/', TagsController::class . '@index');

        // POST /api/tags
        Route::post('/', TagsController::class . '@store');

        // PUT /api/tags/:tag
        Route::put('{tag}', TagsController::class . '@update');
    });
});
