<?php

use App\Application\Http\Controllers\Backend\AttachmentsController;
use App\Application\Http\Controllers\Backend\DashboardController;
use App\Application\Http\Controllers\Backend\PagesController;
use App\Application\Http\Controllers\Backend\SignInController;
use App\Application\Http\Controllers\Backend\TagsController;

Route::domain('backend.' . env('APP_DOMAIN'))->group(function () {
    Route::redirect('/', 'auth');
    Route::redirect('auth', 'auth/in');

    // /auth
    Route::group(['prefix' => 'auth'], function () {
        // /auth/in
        Route::get('in', SignInController::class . '@in')
            ->name('backend.auth.in');

        // /auth/in
        Route::post('in', SignInController::class . '@doIn')
            ->name('backend.auth.do-in');

        // /auth/out
        Route::post('out', SignInController::class . '@out')
            ->name('backend.auth.out');
    });

    Route::group(['middleware' => 'auth'], function () {
        // /dashboard
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', DashboardController::class . '@index')
                ->name('backend.dashboard.index');
        });

        // /attachments
        Route::group(['prefix' => 'attachments'], function () {
            // /attachments
            Route::post('/', AttachmentsController::class . '@store')
                ->name('backend.attachments.store');

            // /attachments/{attachment}/download
            Route::get('{attachment}/download', AttachmentsController::class . '@download')
                ->name('backend.attachments.download');
        });

        // /pages/search
        Route::get('pages/search', PagesController::class . '@search')
            ->name('backend.pages.search');

        // /pages
        Route::resource('pages', PagesController::class, [
            'as' => 'backend',
        ]);

        // /tags/search
        Route::get('tags/search', TagsController::class . '@search')
            ->name('backend.tags.search');

        // /tags
        Route::resource('tags', TagsController::class, [
            'as' => 'backend',
        ]);

        // /about
        Route::get('about')
            ->name('backend.about.index');
    });
});
